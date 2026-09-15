<?php
/** Contact form and newsletter subscription. */
$errors = [];
$old    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();

    // Newsletter form posts here too.
    if (post('action') === 'subscribe') {
        $email = filter_var(post('email'), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            flash('That email address does not look valid.', 'error');
        } else {
            $exists = fetchValue('SELECT id FROM subscribers WHERE email = ?', [$email]);
            if ($exists) {
                flash('You are already subscribed. Thank you!', 'info');
            } else {
                insert('subscribers', ['email' => $email]);
                flash('Subscribed. We will email you before every major event.', 'success');
            }
        }
        redirect('contact');
    }

    $old = [
        'name'    => post('name'),
        'email'   => post('email'),
        'phone'   => post('phone'),
        'subject' => post('subject'),
        'message' => post('message'),
    ];

    if ($old['name'] === '' || mb_strlen($old['name']) < 2) {
        $errors['name'] = 'Please tell us your name.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address so we can reply.';
    }
    if ($old['subject'] === '') {
        $errors['subject'] = 'Add a short subject.';
    }
    if (mb_strlen($old['message']) < 10) {
        $errors['message'] = 'Please write at least a sentence or two.';
    }

    if (!$errors) {
        insert('contacts', [
            'name'    => $old['name'],
            'email'   => $old['email'],
            'phone'   => $old['phone'] ?: null,
            'subject' => $old['subject'],
            'message' => $old['message'],
        ]);
        flash('Message received. The committee usually replies within two working days.', 'success');
        redirect('contact');
    }
    flash('Please correct the highlighted fields.', 'error');
}

page('contact', [
    'title'       => 'Contact Us',
    'description' => 'Reach the DUETCS executive committee — questions, sponsorship, collaboration or feedback.',
    'subtitle'    => 'Questions, sponsorship, collaboration or a problem with the site — this reaches the committee.',
    'errors'      => $errors,
    'old'         => $old,
]);
