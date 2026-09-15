<?php
/** Login, registration, logout, member dashboard and profile. */
$action = $GLOBALS['ROUTE_VERB'];

/* ---------------------------------------------------------------- logout */
if ($action === 'logout') {
    logoutUser();
    flash('You have been signed out.', 'success');
    redirect('');
}

/* ----------------------------------------------------------------- login */
if ($action === 'login') {
    if (isLoggedIn()) {
        redirect(isAdmin() ? 'admin' : 'dashboard');
    }

    $errors = [];
    $old    = ['identifier' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        $old['identifier'] = post('identifier');
        $password = (string) ($_POST['password'] ?? '');

        // Simple throttle: five failures locks the form for five minutes.
        $tries = $_SESSION['login_tries'] ?? ['n' => 0, 'until' => 0];
        if ($tries['n'] >= 5 && time() < $tries['until']) {
            $wait = (int) ceil(($tries['until'] - time()) / 60);
            $errors['identifier'] = "Too many failed attempts. Try again in {$wait} minute(s).";
        } elseif ($old['identifier'] === '' || $password === '') {
            $errors['identifier'] = 'Enter your username or email and your password.';
        } elseif (attemptLogin($old['identifier'], $password)) {
            unset($_SESSION['login_tries']);
            $intended = $_SESSION['intended'] ?? null;
            unset($_SESSION['intended']);
            flash('Welcome back, ' . (currentUser()['name'] ?? '') . '.', 'success');
            redirect($intended ?: (isAdmin() ? 'admin' : 'dashboard'));
        } else {
            $tries['n']++;
            $tries['until'] = time() + 300;
            $_SESSION['login_tries'] = $tries;
            // Deliberately vague: never reveal which half was wrong.
            $errors['identifier'] = 'Those credentials do not match an active account.';
        }
    }

    page('login', [
        'title'       => 'Sign In',
        'description' => 'Sign in to your DUETCS account.',
        'errors'      => $errors,
        'old'         => $old,
    ]);
    return;
}

/* -------------------------------------------------------------- register */
if ($action === 'register') {
    if (isLoggedIn()) {
        redirect('dashboard');
    }

    $errors = [];
    $old    = ['name' => '', 'username' => '', 'email' => '', 'phone' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        foreach (array_keys($old) as $k) {
            $old[$k] = post($k);
        }
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['password_confirm'] ?? '');

        if (mb_strlen($old['name']) < 3) {
            $errors['name'] = 'Enter your full name.';
        }
        if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $old['username'])) {
            $errors['username'] = 'Username: 3-30 characters, letters, numbers and underscore only.';
        } elseif (fetchValue('SELECT id FROM users WHERE username = ?', [$old['username']])) {
            $errors['username'] = 'That username is taken.';
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        } elseif (fetchValue('SELECT id FROM users WHERE email = ?', [$old['email']])) {
            $errors['email'] = 'An account already exists for this email.';
        }
        if ($old['phone'] !== '' && !preg_match('/^01[3-9]\d{8}$/', preg_replace('/\D/', '', $old['phone']))) {
            $errors['phone'] = 'Enter a valid Bangladeshi mobile number, or leave it blank.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Use at least 8 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            $errors['password'] = 'Include at least one letter and one number.';
        }
        if ($password !== $confirm) {
            $errors['password_confirm'] = 'The two passwords do not match.';
        }
        if (!isset($_POST['agree'])) {
            $errors['agree'] = 'You must accept the code of conduct.';
        }

        if (!$errors) {
            $id = insert('users', [
                'name'     => $old['name'],
                'username' => $old['username'],
                'email'    => $old['email'],
                'phone'    => $old['phone'] ? preg_replace('/\D/', '', $old['phone']) : null,
                // Hashed with bcrypt — the plaintext is never stored anywhere.
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role'     => 'user',
                'status'   => 'active',
            ]);
            session_regenerate_id(true);
            $_SESSION['user_id'] = $id;
            resetAuthCache();
            $intended = $_SESSION['intended'] ?? null;
            unset($_SESSION['intended']);
            flash('Account created. Welcome to DUETCS.', 'success');
            redirect($intended ?: 'membership/apply');
        }
        flash('Please correct the highlighted fields.', 'error');
    }

    page('register', [
        'title'       => 'Create an Account',
        'description' => 'Create your DUETCS account to apply for membership.',
        'errors'      => $errors,
        'old'         => $old,
    ]);
    return;
}

/* ------------------------------------------------------------- dashboard */
if ($action === 'dashboard') {
    requireLogin();
    $me     = currentUser();
    $member = currentMember();

    page('dashboard', [
        'title'       => 'Your Dashboard',
        'description' => 'Your DUETCS membership, payments and certificates.',
        'subtitle'    => 'Everything tied to your account, in one place.',
        'me'          => $me,
        'member'      => $member,
        'plan'        => $member && $member['plan_id'] ? fetchOne('SELECT * FROM membership_plans WHERE id = ?', [$member['plan_id']]) : null,
        'payments'    => $member ? fetchAll('SELECT * FROM payments WHERE member_id = ? ORDER BY id DESC', [$member['id']]) : [],
        'certs'       => fetchAll('SELECT * FROM certificates WHERE holder_email = ? AND is_revoked = 0 ORDER BY issued_on DESC', [$me['email']]),
        'upcoming'    => fetchAll("SELECT * FROM events WHERE status = 'upcoming' ORDER BY start_datetime ASC LIMIT 3"),
    ]);
    return;
}

/* --------------------------------------------------------------- profile */
if ($action === 'profile') {
    requireLogin();
    $me = currentUser();
    $errors = [];
    $old = ['name' => $me['name'], 'email' => $me['email'], 'phone' => $me['phone'] ?? '', 'bio' => $me['bio'] ?? ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();

        if (post('action') === 'password') {
            $current = (string) ($_POST['current_password'] ?? '');
            $new     = (string) ($_POST['new_password'] ?? '');
            $confirm = (string) ($_POST['new_password_confirm'] ?? '');

            if (!password_verify($current, $me['password'])) {
                $errors['current_password'] = 'Your current password is not correct.';
            }
            if (strlen($new) < 8 || !preg_match('/[A-Za-z]/', $new) || !preg_match('/\d/', $new)) {
                $errors['new_password'] = 'Use at least 8 characters with a letter and a number.';
            }
            if ($new !== $confirm) {
                $errors['new_password_confirm'] = 'The two passwords do not match.';
            }
            if (!$errors) {
                update('users', ['password' => password_hash($new, PASSWORD_DEFAULT)], 'id = :uid', ['uid' => $me['id']]);
                flash('Password updated.', 'success');
                redirect('profile');
            }
        } else {
            foreach (array_keys($old) as $k) {
                $old[$k] = post($k);
            }
            if (mb_strlen($old['name']) < 3) {
                $errors['name'] = 'Enter your full name.';
            }
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Enter a valid email address.';
            } elseif (fetchValue('SELECT id FROM users WHERE email = ? AND id <> ?', [$old['email'], $me['id']])) {
                $errors['email'] = 'Another account already uses that email.';
            }
            if ($old['phone'] !== '' && !preg_match('/^01[3-9]\d{8}$/', preg_replace('/\D/', '', $old['phone']))) {
                $errors['phone'] = 'Enter a valid Bangladeshi mobile number, or leave it blank.';
            }

            $avatarError = null;
            $avatar = saveUpload('avatar', 'members', $avatarError);
            if ($avatarError) {
                $errors['avatar'] = $avatarError;
            }

            if (!$errors) {
                $data = [
                    'name'  => $old['name'],
                    'email' => $old['email'],
                    'phone' => $old['phone'] ? preg_replace('/\D/', '', $old['phone']) : null,
                    'bio'   => $old['bio'] ?: null,
                ];
                if ($avatar) {
                    deleteUpload($me['avatar'], 'members');
                    $data['avatar'] = $avatar;
                }
                update('users', $data, 'id = :uid', ['uid' => $me['id']]);
                flash('Profile updated.', 'success');
                redirect('profile');
            }
        }
        flash('Please correct the highlighted fields.', 'error');
    }

    page('profile', [
        'title'       => 'Your Profile',
        'description' => 'Update your DUETCS account details.',
        'subtitle'    => 'Account details and password.',
        'me'          => $me,
        'errors'      => $errors,
        'old'         => $old,
    ]);
    return;
}

notFound();
