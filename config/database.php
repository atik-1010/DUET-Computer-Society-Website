<?php
/**
 * PDO database connection (singleton).
 * Prepared statements everywhere — no string interpolation into SQL.
 */

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT
         . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Managed providers refuse unencrypted connections. With a CA bundle the
    // server certificate is verified; without one, TLS is still used but the
    // certificate is not checked — weaker, yet the only option when the host
    // supplies no CA file, and still far better than a refused connection.
    if (DB_SSL) {
        if (DB_SSL_CA !== '') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = DB_SSL_CA;
        } else {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
    }

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        http_response_code(500);
        $hint = DEBUG ? htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') : 'Database unavailable.';

        // Always report WHICH settings were found, never their values. Variable
        // names are not secrets, so this is safe on a public error page, and it
        // answers the one thing the generic message cannot: whether the host's
        // environment variables reached the app at all, or whether it fell back
        // to the local defaults and tried to reach a database on itself.
        $checklist = '';
        foreach (['DUETCS_DB_HOST', 'DUETCS_DB_PORT', 'DUETCS_DB_NAME',
                  'DUETCS_DB_USER', 'DUETCS_DB_PASS', 'DUETCS_DB_SSL'] as $__k) {
            $__set = function_exists('envVar') ? envVar($__k) !== null : getenv($__k) !== false;
            $checklist .= '<li>' . $__k . ' &mdash; '
                . ($__set ? 'set' : '<strong style="color:#e11d48">not set</strong>') . '</li>';
        }
        $usingDefaults = DB_HOST === 'localhost';
        exit('<div style="font:16px/1.6 system-ui;max-width:640px;margin:80px auto;padding:32px;border:1px solid #e11d48;border-radius:12px">'
           . '<h2 style="margin:0 0 12px">Database connection failed</h2>'
           . '<p style="color:#64748b">' . $hint . '</p>'
           // Under DEBUG, show what the connection was actually attempted with.
           // This answers the first question in any failure -- whether the host's
           // environment variables reached the app at all, or whether it quietly
           // fell back to the local defaults. The password is never printed.
           . (DEBUG ? '<p style="font:13px/1.7 ui-monospace,SFMono-Regular,monospace;color:#64748b;background:#f8fafc;padding:12px;border-radius:8px">'
               . 'host = '     . htmlspecialchars(DB_HOST, ENT_QUOTES, 'UTF-8') . '<br>'
               . 'port = '     . DB_PORT . '<br>'
               . 'database = ' . htmlspecialchars(DB_NAME, ENT_QUOTES, 'UTF-8') . '<br>'
               . 'user = '     . htmlspecialchars(DB_USER, ENT_QUOTES, 'UTF-8') . '<br>'
               . 'password = ' . (DB_PASS === '' ? '(empty)' : '(set, ' . strlen(DB_PASS) . ' characters)') . '<br>'
               . 'tls = '      . (DB_SSL ? (DB_SSL_CA !== '' ? 'on, verifying CA' : 'on, not verifying') : 'off')
               . '</p>' : '')
           . '<p style="margin:20px 0 6px;font-weight:600">Configuration found</p>'
           . '<ul style="font:13px/1.8 ui-monospace,SFMono-Regular,monospace;color:#64748b;margin:0;padding-left:20px">'
           . $checklist . '</ul>'
           . ($usingDefaults
               ? '<p style="color:#e11d48;margin-top:16px">The app is falling back to <code>localhost</code>, '
                 . 'so no database host reached it. On Vercel, add these as Environment Variables, make sure they are '
                 . 'enabled for the <strong>Production</strong> environment, then redeploy.</p>'
               : '')
           . (DEBUG ? '<p style="color:#64748b">Locally: start MySQL in XAMPP, then import <code>database/schema.sql</code> via phpMyAdmin.</p>' : '')
           . '</div>');
    }
    return $pdo;
}

/** Run a query with bound params and return the statement. */
function q(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/** Fetch all rows. */
function fetchAll(string $sql, array $params = []): array
{
    return q($sql, $params)->fetchAll();
}

/** Fetch a single row or null. */
function fetchOne(string $sql, array $params = []): ?array
{
    $row = q($sql, $params)->fetch();
    return $row === false ? null : $row;
}

/** Fetch a single scalar value. */
function fetchValue(string $sql, array $params = [], $default = null)
{
    $val = q($sql, $params)->fetchColumn();
    return $val === false ? $default : $val;
}

/** Insert an associative array into a table, return the new id. */
function insert(string $table, array $data): int
{
    $cols = array_keys($data);
    $sql  = 'INSERT INTO `' . $table . '` (`' . implode('`,`', $cols) . '`) VALUES (:' . implode(',:', $cols) . ')';
    q($sql, $data);
    return (int) db()->lastInsertId();
}

/** Update rows matching a WHERE clause. */
function update(string $table, array $data, string $where, array $whereParams = []): int
{
    $sets = [];
    foreach (array_keys($data) as $c) {
        $sets[] = "`$c` = :$c";
    }
    $sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $sets) . ' WHERE ' . $where;
    return q($sql, array_merge($data, $whereParams))->rowCount();
}
