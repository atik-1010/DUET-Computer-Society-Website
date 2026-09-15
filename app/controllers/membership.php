<?php
/**
 * Membership: plans → application → mobile wallet payment → verification.
 *
 * Payments are settled out of band: the student pays from their own bKash,
 * Nagad or Rocket app to the society's number, then submits the transaction
 * ID here. The Treasurer matches it against the wallet statement and approves
 * it in the admin panel. No card data or wallet credentials ever touch this
 * application.
 */
$step = $params[0] ?? '';

$plans = fetchAll('SELECT * FROM membership_plans WHERE is_active = 1 ORDER BY sort_order');
$methods = fetchAll('SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY sort_order');

/* ------------------------------------------------------------- overview */
if ($step === '') {
    page('membership', [
        'title'       => 'Membership',
        'description' => 'Join DUET Computer Society — plans, benefits and how to pay with bKash, Nagad or Rocket.',
        'subtitle'    => 'Open to every DUET student, from every department, in every series.',
        'plans'       => $plans,
        'methods'     => $methods,
        'member'      => currentMember(),
        'stats'       => [
            'members'  => (int) setting('stat_members', 900),
            'approved' => (int) fetchValue("SELECT COUNT(*) FROM members WHERE status='approved'", [], 0),
        ],
    ]);
    return;
}

/* ---------------------------------------------------------- application */
if ($step === 'apply') {
    if (!isLoggedIn()) {
        $_SESSION['intended'] = 'membership/apply';
        flash('Create an account or sign in first — your application is linked to it.', 'info');
        redirect('register');
    }

    $me = currentUser();
    $existing = currentMember();
    if ($existing && in_array($existing['status'], ['approved', 'pending'], true)) {
        redirect('membership/status');
    }

    $errors = [];
    $old = [
        'full_name'  => $existing['full_name']  ?? $me['name'],
        'email'      => $existing['email']      ?? $me['email'],
        'phone'      => $existing['phone']      ?? ($me['phone'] ?? ''),
        'student_id' => $existing['student_id'] ?? '',
        'department' => $existing['department'] ?? 'CSE',
        'series'     => $existing['series']     ?? '',
        'semester'   => $existing['semester']   ?? '',
        'blood_group'=> $existing['blood_group']?? '',
        'address'    => $existing['address']    ?? '',
        'interests'  => $existing['interests']  ?? '',
        'motivation' => $existing['motivation'] ?? '',
        'plan_id'    => $existing['plan_id']    ?? ($plans[0]['id'] ?? null),
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        foreach (array_keys($old) as $k) {
            $old[$k] = post($k);
        }
        $old['interests'] = implode(', ', array_map('trim', (array) ($_POST['interests'] ?? [])));

        if (mb_strlen($old['full_name']) < 3)                       $errors['full_name']  = 'Enter your full name as it appears on your student ID.';
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL))      $errors['email']      = 'Enter a valid email address.';
        if (!preg_match('/^01[3-9]\d{8}$/', preg_replace('/\D/', '', $old['phone']))) {
            $errors['phone'] = 'Enter a valid Bangladeshi mobile number, e.g. 01712345678.';
        }
        if (!preg_match('/^[A-Za-z0-9\-\/]{4,20}$/', $old['student_id'])) $errors['student_id'] = 'Enter your DUET student ID.';
        if ($old['department'] === '')                              $errors['department'] = 'Select your department.';
        if ($old['series'] === '')                                  $errors['series']     = 'Select your session/series.';
        if (!$old['plan_id'])                                       $errors['plan_id']    = 'Choose a membership plan.';
        if (!isset($_POST['agree']))                                $errors['agree']      = 'You must accept the code of conduct.';

        $plan = fetchOne('SELECT * FROM membership_plans WHERE id = ? AND is_active = 1', [$old['plan_id']]);
        if (!$plan) {
            $errors['plan_id'] = 'That membership plan is not available.';
        }

        // One application per student ID, unless it belongs to this user already.
        $clash = fetchOne('SELECT id, user_id FROM members WHERE student_id = ? LIMIT 1', [$old['student_id']]);
        if ($clash && (int) $clash['user_id'] !== (int) $me['id']) {
            $errors['student_id'] = 'An application already exists for this student ID.';
        }

        $photoError = null;
        $photo = saveUpload('photo', 'members', $photoError);
        if ($photoError) {
            $errors['photo'] = $photoError;
        }

        if (!$errors) {
            $data = [
                'user_id'     => $me['id'],
                'plan_id'     => $plan['id'],
                'full_name'   => $old['full_name'],
                'email'       => $old['email'],
                'phone'       => preg_replace('/\D/', '', $old['phone']),
                'student_id'  => $old['student_id'],
                'department'  => $old['department'],
                'series'      => $old['series'],
                'semester'    => $old['semester'] ?: null,
                'blood_group' => $old['blood_group'] ?: null,
                'address'     => $old['address'] ?: null,
                'interests'   => $old['interests'] ?: null,
                'motivation'  => $old['motivation'] ?: null,
                'status'      => 'awaiting_payment',
            ];
            if ($photo) {
                $data['photo'] = $photo;
            }

            if ($existing) {
                update('members', $data, 'id = :mid', ['mid' => $existing['id']]);
            } else {
                insert('members', $data);
            }
            flash('Application saved. One step left — submit your payment.', 'success');
            redirect('membership/payment');
        }
        flash('Please correct the highlighted fields.', 'error');
    }

    page('membership-apply', [
        'title'       => 'Membership Application',
        'description' => 'Apply for DUETCS membership.',
        'subtitle'    => 'Step 1 of 2 — tell us who you are.',
        'plans'       => $plans,
        'errors'      => $errors,
        'old'         => $old,
    ]);
    return;
}

/* -------------------------------------------------------------- payment */
if ($step === 'payment') {
    requireLogin();
    $member = currentMember();
    if (!$member) {
        flash('Complete your application first.', 'info');
        redirect('membership/apply');
    }
    if ($member['status'] === 'approved') {
        redirect('membership/status');
    }

    $pending = fetchOne(
        "SELECT * FROM payments WHERE member_id = ? AND status IN ('pending','verified') ORDER BY id DESC LIMIT 1",
        [$member['id']]
    );
    if ($pending) {
        redirect('membership/status');
    }

    $plan = $member['plan_id'] ? fetchOne('SELECT * FROM membership_plans WHERE id = ?', [$member['plan_id']]) : null;
    $errors = [];
    $old = [
        'method'        => 'bkash',
        'plan_id'       => $member['plan_id'],
        'sender_number' => $member['phone'],
        'trx_id'        => '',
        'amount'        => $plan['fee'] ?? MEMBERSHIP_FEE,
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        foreach (array_keys($old) as $k) {
            $old[$k] = post($k);
        }

        $method = fetchOne('SELECT * FROM payment_methods WHERE code = ? AND is_active = 1', [$old['method']]);
        if (!$method) {
            $errors['method'] = 'Choose one of the available wallets.';
        }

        $chosenPlan = fetchOne('SELECT * FROM membership_plans WHERE id = ? AND is_active = 1', [$old['plan_id']]);
        if (!$chosenPlan) {
            $errors['plan_id'] = 'Choose a membership plan.';
        }

        if (!preg_match('/^01[3-9]\d{8}$/', preg_replace('/\D/', '', $old['sender_number']))) {
            $errors['sender_number'] = 'Enter the mobile number you paid from.';
        }

        // bKash/Nagad TrxIDs are ~10 alphanumerics; Rocket's are longer. Accept 6-20.
        $trx = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $old['trx_id']));
        if (strlen($trx) < 6 || strlen($trx) > 20) {
            $errors['trx_id'] = 'Enter the Transaction ID exactly as it appears in your confirmation SMS.';
        } elseif (fetchValue('SELECT id FROM payments WHERE method = ? AND trx_id = ?', [$old['method'], $trx])) {
            $errors['trx_id'] = 'This transaction ID has already been submitted.';
        }

        $amount = (float) $old['amount'];
        if ($chosenPlan && abs($amount - (float) $chosenPlan['fee']) > 0.01) {
            $errors['amount'] = 'The amount must match the selected plan exactly (' . taka($chosenPlan['fee']) . ').';
        }

        $shotError = null;
        $shot = saveUpload('screenshot', 'payments', $shotError);
        if ($shotError) {
            $errors['screenshot'] = $shotError;
        }

        if (!$errors) {
            $reference = 'DCS-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            insert('payments', [
                'member_id'       => $member['id'],
                'plan_id'         => $chosenPlan['id'],
                'reference'       => $reference,
                'method'          => $method['code'],
                'sender_number'   => preg_replace('/\D/', '', $old['sender_number']),
                'receiver_number' => $method['number'],
                'trx_id'          => $trx,
                'amount'          => $chosenPlan['fee'],
                'screenshot'      => $shot,
                'status'          => 'pending',
            ]);
            update('members', ['status' => 'pending', 'plan_id' => $chosenPlan['id']], 'id = :mid', ['mid' => $member['id']]);
            flash('Payment submitted. The Treasurer usually verifies within 24 hours.', 'success');
            redirect('membership/status');
        }
        flash('Please correct the highlighted fields.', 'error');
    }

    page('membership-payment', [
        'title'       => 'Membership Payment',
        'description' => 'Pay your DUETCS membership fee with bKash, Nagad or Rocket.',
        'subtitle'    => 'Step 2 of 2 — pay from your own wallet, then record the transaction here.',
        'member'      => $member,
        'plans'       => $plans,
        'methods'     => $methods,
        'errors'      => $errors,
        'old'         => $old,
        'note'        => setting('payment_note', ''),
    ]);
    return;
}

/* --------------------------------------------------------------- status */
if ($step === 'status') {
    requireLogin();
    $member = currentMember();
    if (!$member) {
        flash('You have not applied for membership yet.', 'info');
        redirect('membership/apply');
    }

    page('membership-status', [
        'title'       => 'Membership Status',
        'description' => 'Track your DUETCS membership application and payment.',
        'subtitle'    => 'Where your application currently stands.',
        'member'      => $member,
        'plan'        => $member['plan_id'] ? fetchOne('SELECT * FROM membership_plans WHERE id = ?', [$member['plan_id']]) : null,
        'payments'    => fetchAll('SELECT * FROM payments WHERE member_id = ? ORDER BY id DESC', [$member['id']]),
    ]);
    return;
}

notFound();
