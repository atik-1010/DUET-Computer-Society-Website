<?php
/**
 * Admin panel. Section 0 selects the area; the generic CRUD engine handles
 * every content type declared in app/admin_resources.php.
 */
requireAdmin();
require_once BASE_PATH . '/app/admin_resources.php';

$resources = adminResources();
$section   = $params[0] ?? '';
$action    = $params[1] ?? 'index';
$id        = isset($params[2]) ? (int) $params[2] : 0;

/** Render an admin page inside the admin layout. */
function adminPage(string $template, array $data = []): void
{
    $GLOBALS['PAGE_DATA'] = $data;
    require BASE_PATH . '/views/layouts/admin-header.php';
    view('admin/' . $template, $data);
    require BASE_PATH . '/views/layouts/admin-footer.php';
}

/* ============================================================== dashboard */
if ($section === '') {
    $counts = [
        'members'   => (int) fetchValue('SELECT COUNT(*) FROM members', [], 0),
        'pendingM'  => (int) fetchValue("SELECT COUNT(*) FROM members WHERE status='pending'", [], 0),
        'payments'  => (int) fetchValue("SELECT COUNT(*) FROM payments WHERE status='pending'", [], 0),
        'revenue'   => (float) fetchValue("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='verified'", [], 0),
        'events'    => (int) fetchValue('SELECT COUNT(*) FROM events', [], 0),
        'posts'     => (int) fetchValue('SELECT COUNT(*) FROM posts', [], 0),
        'messages'  => (int) fetchValue('SELECT COUNT(*) FROM contacts WHERE is_read = 0', [], 0),
        'certs'     => (int) fetchValue('SELECT COUNT(*) FROM certificates', [], 0),
    ];

    adminPage('dashboard', [
        'title'    => 'Admin Dashboard',
        'counts'   => $counts,
        'payments' => fetchAll(
            "SELECT p.*, m.full_name, m.student_id
             FROM payments p JOIN members m ON m.id = p.member_id
             WHERE p.status = 'pending' ORDER BY p.id DESC LIMIT 6"
        ),
        'members'  => fetchAll("SELECT * FROM members ORDER BY id DESC LIMIT 6"),
        'messages' => fetchAll('SELECT * FROM contacts ORDER BY id DESC LIMIT 5'),
        'monthly'  => fetchAll(
            "SELECT DATE_FORMAT(verified_at, '%Y-%m') AS ym, SUM(amount) AS total, COUNT(*) AS n
             FROM payments WHERE status='verified' AND verified_at IS NOT NULL
             GROUP BY ym ORDER BY ym DESC LIMIT 6"
        ),
    ]);
    return;
}

/* ================================================================ members */
if ($section === 'members') {
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        $member = fetchOne('SELECT * FROM members WHERE id = ?', [$id]);
        if (!$member) {
            flash('Member not found.', 'error');
            redirect('admin/members');
        }
        $status = post('status');
        if (!in_array($status, ['pending', 'awaiting_payment', 'approved', 'rejected', 'expired'], true)) {
            flash('Unknown status.', 'error');
            redirect('admin/members');
        }

        $data = ['status' => $status, 'admin_note' => post('admin_note') ?: null];

        if ($status === 'approved') {
            $data['approved_at'] = date('Y-m-d H:i:s');
            if (empty($member['member_no'])) {
                $data['member_no'] = 'DCS-' . date('y') . '-' . str_pad((string) $member['id'], 4, '0', STR_PAD_LEFT);
            }
            $plan = $member['plan_id'] ? fetchOne('SELECT * FROM membership_plans WHERE id = ?', [$member['plan_id']]) : null;
            $months = $plan ? (int) $plan['duration_months'] : 12;
            $data['expires_on'] = $months > 0 ? date('Y-m-d', strtotime("+{$months} months")) : null;

            // Promote the linked account so member-only areas open up.
            if ($member['user_id']) {
                $u = fetchOne('SELECT role FROM users WHERE id = ?', [$member['user_id']]);
                if ($u && $u['role'] === 'user') {
                    update('users', ['role' => 'member'], 'id = :uid', ['uid' => $member['user_id']]);
                }
            }
        }

        update('members', $data, 'id = :mid', ['mid' => $id]);
        flash('Member updated.', 'success');
        redirect('admin/members');
    }

    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        $member = fetchOne('SELECT * FROM members WHERE id = ?', [$id]);
        if ($member) {
            deleteUpload($member['photo'], 'members');
            q('DELETE FROM members WHERE id = ?', [$id]);
            flash('Member application deleted.', 'success');
        }
        redirect('admin/members');
    }

    $status = get('status', 'all');
    $search = get('q', '');
    $where  = [];
    $bind   = [];
    if (in_array($status, ['pending', 'awaiting_payment', 'approved', 'rejected', 'expired'], true)) {
        $where[] = 'm.status = :st';
        $bind['st'] = $status;
    }
    if ($search !== '') {
        $where[] = '(m.full_name LIKE :q1 OR m.student_id LIKE :q2 OR m.email LIKE :q3 OR m.member_no LIKE :q4)';
        $like = '%' . $search . '%';
        $bind += ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
    }
    $sql = 'SELECT m.*, pl.name AS plan_name FROM members m
            LEFT JOIN membership_plans pl ON pl.id = m.plan_id'
         . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
         . ' ORDER BY m.id DESC';

    $counts = ['all' => (int) fetchValue('SELECT COUNT(*) FROM members', [], 0)];
    foreach (fetchAll('SELECT status, COUNT(*) c FROM members GROUP BY status') as $r) {
        $counts[$r['status']] = (int) $r['c'];
    }

    adminPage('members', [
        'title'   => 'Members',
        'members' => fetchAll($sql, $bind),
        'status'  => $status,
        'search'  => $search,
        'counts'  => $counts,
    ]);
    return;
}

/* =============================================================== payments */
if ($section === 'payments') {
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        $pay = fetchOne('SELECT * FROM payments WHERE id = ?', [$id]);
        if (!$pay) {
            flash('Payment not found.', 'error');
            redirect('admin/payments');
        }
        $status = post('status');
        if (!in_array($status, ['pending', 'verified', 'rejected'], true)) {
            flash('Unknown status.', 'error');
            redirect('admin/payments');
        }

        update('payments', [
            'status'      => $status,
            'admin_note'  => post('admin_note') ?: null,
            'verified_at' => $status === 'pending' ? null : date('Y-m-d H:i:s'),
            'verified_by' => $status === 'pending' ? null : currentUser()['id'],
        ], 'id = :pid', ['pid' => $id]);

        // A verified payment approves the membership it belongs to.
        if ($status === 'verified') {
            $member = fetchOne('SELECT * FROM members WHERE id = ?', [$pay['member_id']]);
            if ($member && $member['status'] !== 'approved') {
                $plan   = $pay['plan_id'] ? fetchOne('SELECT * FROM membership_plans WHERE id = ?', [$pay['plan_id']]) : null;
                $months = $plan ? (int) $plan['duration_months'] : 12;
                update('members', [
                    'status'      => 'approved',
                    'approved_at' => date('Y-m-d H:i:s'),
                    'member_no'   => $member['member_no'] ?: 'DCS-' . date('y') . '-' . str_pad((string) $member['id'], 4, '0', STR_PAD_LEFT),
                    'expires_on'  => $months > 0 ? date('Y-m-d', strtotime("+{$months} months")) : null,
                ], 'id = :mid', ['mid' => $member['id']]);

                if ($member['user_id']) {
                    $u = fetchOne('SELECT role FROM users WHERE id = ?', [$member['user_id']]);
                    if ($u && $u['role'] === 'user') {
                        update('users', ['role' => 'member'], 'id = :uid', ['uid' => $member['user_id']]);
                    }
                }
            }
            flash('Payment verified and membership approved.', 'success');
        } elseif ($status === 'rejected') {
            update('members', ['status' => 'awaiting_payment'], 'id = :mid', ['mid' => $pay['member_id']]);
            flash('Payment rejected. The applicant can submit a new transaction.', 'warning');
        } else {
            flash('Payment moved back to pending.', 'info');
        }
        redirect('admin/payments');
    }

    $status = get('status', 'pending');
    $search = get('q', '');
    $where  = [];
    $bind   = [];
    if (in_array($status, ['pending', 'verified', 'rejected'], true)) {
        $where[] = 'p.status = :st';
        $bind['st'] = $status;
    }
    if ($search !== '') {
        $where[] = '(p.trx_id LIKE :q1 OR p.reference LIKE :q2 OR m.full_name LIKE :q3 OR p.sender_number LIKE :q4)';
        $like = '%' . $search . '%';
        $bind += ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
    }
    $sql = 'SELECT p.*, m.full_name, m.student_id, m.email AS member_email, pl.name AS plan_name
            FROM payments p
            JOIN members m ON m.id = p.member_id
            LEFT JOIN membership_plans pl ON pl.id = p.plan_id'
         . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
         . ' ORDER BY p.id DESC';

    $counts = ['all' => (int) fetchValue('SELECT COUNT(*) FROM payments', [], 0)];
    foreach (fetchAll('SELECT status, COUNT(*) c FROM payments GROUP BY status') as $r) {
        $counts[$r['status']] = (int) $r['c'];
    }

    adminPage('payments', [
        'title'    => 'Payments',
        'payments' => fetchAll($sql, $bind),
        'status'   => $status,
        'search'   => $search,
        'counts'   => $counts,
        'totals'   => [
            'verified' => (float) fetchValue("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='verified'", [], 0),
            'pending'  => (float) fetchValue("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='pending'", [], 0),
        ],
    ]);
    return;
}

/* =============================================================== messages */
if ($section === 'messages') {
    if ($action === 'read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        update('contacts', ['is_read' => 1], 'id = :cid', ['cid' => $id]);
        redirect('admin/messages');
    }
    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        q('DELETE FROM contacts WHERE id = ?', [$id]);
        flash('Message deleted.', 'success');
        redirect('admin/messages');
    }

    adminPage('messages', [
        'title'       => 'Messages',
        'messages'    => fetchAll('SELECT * FROM contacts ORDER BY is_read, id DESC'),
        'subscribers' => fetchAll('SELECT * FROM subscribers ORDER BY id DESC'),
    ]);
    return;
}

/* =============================================================== settings */
if ($section === 'settings') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        foreach (($_POST['settings'] ?? []) as $key => $value) {
            $key = preg_replace('/[^a-z0-9_]/i', '', (string) $key);
            if ($key === '') {
                continue;
            }
            q('INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
              [$key, is_string($value) ? trim($value) : '']);
        }
        flash('Settings saved.', 'success');
        redirect('admin/settings');
    }

    $rows = [];
    foreach (fetchAll('SELECT `key`, `value` FROM settings ORDER BY `key`') as $r) {
        $rows[$r['key']] = $r['value'];
    }
    adminPage('settings', ['title' => 'Settings', 'settings' => $rows]);
    return;
}

/* ============================================== generic CRUD for content */
if (!isset($resources[$section])) {
    notFound();
}

$res    = $resources[$section];
$table  = $res['table'];
$fields = $res['fields'];
$folder = $res['upload'] ?? '';

/** Resolve a select field's options, running its SQL when needed. */
function fieldOptions(array $def): array
{
    $opts = $def[4] ?? [];
    if (is_string($opts) && str_starts_with($opts, 'sql:')) {
        $out = [];
        foreach (fetchAll(substr($opts, 4)) as $row) {
            $out[$row['id']] = $row['label'];
        }
        return $out;
    }
    return is_array($opts) ? $opts : [];
}

/* ---- delete ---- */
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $row = fetchOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if ($row) {
        foreach ($fields as $name => $def) {
            if ($def[0] === 'image' && !empty($row[$name])) {
                deleteUpload($row[$name], $folder);
            }
        }
        q("DELETE FROM `$table` WHERE id = ?", [$id]);
        flash($res['singular'] . ' deleted.', 'success');
    }
    redirect('admin/' . $section);
}

/* ---- create / edit ---- */
if ($action === 'create' || $action === 'edit') {
    $row = [];
    if ($action === 'edit') {
        $row = fetchOne("SELECT * FROM `$table` WHERE id = ?", [$id]) ?: [];
        if (!$row) {
            flash('That record no longer exists.', 'error');
            redirect('admin/' . $section);
        }
    }

    $errors = [];
    $old = [];
    foreach ($fields as $name => $def) {
        $old[$name] = $row[$name] ?? ($def[0] === 'checkbox' ? 0 : '');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrfCheck();
        $data = [];

        foreach ($fields as $name => $def) {
            [$type, $label] = [$def[0], $def[1]];
            $rules    = $def[2] ?? '';
            $required = str_contains($rules, 'required');

            if ($type === 'checkbox') {
                $value = isset($_POST[$name]) ? 1 : 0;
                $old[$name] = $value;
                $data[$name] = $value;
                continue;
            }

            if ($type === 'image') {
                $err = null;
                $file = saveUpload($name, $folder, $err);
                if ($err) {
                    $errors[$name] = $err;
                } elseif ($file) {
                    if (!empty($row[$name])) {
                        deleteUpload($row[$name], $folder);
                    }
                    $data[$name] = $file;
                    $old[$name] = $file;
                } elseif ($required && empty($row[$name])) {
                    $errors[$name] = $label . ' is required.';
                }
                continue;
            }

            $value = post($name);
            $old[$name] = $value;

            if ($type === 'slug') {
                if ($value === '') {
                    $srcField = '';
                    if (preg_match('/from:(\w+)/', $rules, $m)) {
                        $srcField = $m[1];
                    }
                    $value = slugify((string) ($old[$srcField] ?? $name));
                } else {
                    $value = slugify($value);
                }
                // Keep slugs unique within the table.
                $base = $value;
                $n = 2;
                while (fetchValue("SELECT id FROM `$table` WHERE slug = ? AND id <> ?", [$value, $id])) {
                    $value = $base . '-' . $n++;
                }
                $old[$name] = $value;
            }

            if ($required && $value === '') {
                $errors[$name] = $label . ' is required.';
                continue;
            }
            if ($value === '') {
                $data[$name] = null;
                continue;
            }

            switch ($type) {
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$name] = $label . ' must be a valid email address.';
                    }
                    break;
                case 'url':
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $errors[$name] = $label . ' must be a valid URL including https://';
                    }
                    break;
                case 'number':
                    if (!is_numeric($value)) {
                        $errors[$name] = $label . ' must be a number.';
                    } else {
                        $value = (int) $value;
                    }
                    break;
                case 'money':
                    if (!is_numeric($value)) {
                        $errors[$name] = $label . ' must be a number.';
                    } else {
                        $value = (float) $value;
                    }
                    break;
                case 'select':
                    $opts = fieldOptions($def);
                    if ($opts && !array_key_exists($value, $opts)) {
                        $errors[$name] = 'Choose a valid option for ' . $label . '.';
                    }
                    break;
                case 'datetime':
                    $value = str_replace('T', ' ', $value);
                    if (strlen($value) === 16) {
                        $value .= ':00';
                    }
                    break;
            }

            $data[$name] = $value;
        }

        if (!$errors) {
            if ($action === 'edit') {
                update($table, $data, 'id = :rid', ['rid' => $id]);
                flash($res['singular'] . ' updated.', 'success');
            } else {
                $id = insert($table, $data);
                flash($res['singular'] . ' created.', 'success');
            }
            redirect('admin/' . $section);
        }
        flash('Please correct the highlighted fields.', 'error');
    }

    adminPage('form', [
        'title'    => ($action === 'create' ? 'New ' : 'Edit ') . strtolower($res['singular']),
        'res'      => $res,
        'section'  => $section,
        'fields'   => $fields,
        'old'      => $old,
        'errors'   => $errors,
        'editing'  => $action === 'edit',
        'recordId' => $id,
    ]);
    return;
}

/* ---- list ---- */
$search = get('q', '');
$where  = '';
$bind   = [];
if ($search !== '' && !empty($res['search'])) {
    $parts = [];
    foreach ($res['search'] as $i => $col) {
        $parts[] = "`$col` LIKE :s$i";
        $bind["s$i"] = '%' . $search . '%';
    }
    $where = 'WHERE ' . implode(' OR ', $parts);
}

$perPage = 20;
$pageNo  = max(1, (int) get('page', 1));
$total   = (int) fetchValue("SELECT COUNT(*) FROM `$table` $where", $bind, 0);
$pages   = max(1, (int) ceil($total / $perPage));
$pageNo  = min($pageNo, $pages);
$offset  = ($pageNo - 1) * $perPage;
$order   = $res['order'] ?? 'id DESC';

// Resolve foreign-key columns to readable labels for the list view.
$lookups = [];
foreach ($res['list'] as $col => $_) {
    if (isset($fields[$col]) && $fields[$col][0] === 'select') {
        $lookups[$col] = fieldOptions($fields[$col]);
    }
}

adminPage('list', [
    'title'   => $res['label'],
    'res'     => $res,
    'section' => $section,
    'rows'    => fetchAll("SELECT * FROM `$table` $where ORDER BY $order LIMIT $perPage OFFSET $offset", $bind),
    'lookups' => $lookups,
    'search'  => $search,
    'pageNo'  => $pageNo,
    'pages'   => $pages,
    'total'   => $total,
]);
