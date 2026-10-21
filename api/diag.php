<?php
/**
 * Temporary deployment diagnostic, reachable at /_diag.
 *
 * It answers the three things a failing deploy cannot otherwise tell us: where
 * the runtime's working directory is, which project files actually reached the
 * function bundle, and whether the host's environment variables arrived.
 *
 * It deliberately does not require config.php, so it still works while the
 * database is unreachable. Environment variables are reported by NAME ONLY --
 * no value is ever printed. Delete this file once the deployment is healthy.
 */
header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);

echo "=== runtime ===\n";
printf("  php             : %s\n", PHP_VERSION);
printf("  sapi            : %s\n", PHP_SAPI);
printf("  __DIR__         : %s\n", __DIR__);
printf("  project root    : %s\n", $root);
printf("  getcwd()        : %s\n", getcwd() ?: '(false)');
printf("  DOCUMENT_ROOT   : %s\n", $_SERVER['DOCUMENT_ROOT'] ?? '(unset)');
printf("  SCRIPT_FILENAME : %s\n", $_SERVER['SCRIPT_FILENAME'] ?? '(unset)');
printf("  SCRIPT_NAME     : %s\n", $_SERVER['SCRIPT_NAME'] ?? '(unset)');
printf("  REQUEST_URI     : %s\n", $_SERVER['REQUEST_URI'] ?? '(unset)');

echo "\n=== files the app needs (relative to project root) ===\n";
foreach ([
    'index.php',
    'config/config.php',
    'config/database.php',
    'app/helpers.php',
    'app/session_db.php',
    'app/controllers/home.php',
    'views/pages/home.php',
    'assets/css/main.css',
    'assets/img/logo.png',
    'uploads/committee',
] as $rel) {
    printf("  %-28s %s\n", $rel, file_exists($root . '/' . $rel) ? 'present' : 'MISSING');
}

echo "\n=== project root listing ===\n";
$entries = @scandir($root);
if ($entries === false) {
    echo "  (cannot read)\n";
} else {
    foreach ($entries as $f) {
        if ($f === '.' || $f === '..') continue;
        printf("  %-22s %s\n", $f, is_dir($root . '/' . $f) ? '[dir]' : '');
    }
}

echo "\n=== uploads/committee sample (first 3) ===\n";
$u = @scandir($root . '/uploads/committee');
if ($u === false) {
    echo "  (directory not in the bundle)\n";
} else {
    $n = 0;
    foreach ($u as $f) {
        if ($f === '.' || $f === '..') continue;
        echo "  $f\n";
        if (++$n >= 3) break;
    }
    if ($n === 0) echo "  (empty)\n";
}

echo "\n=== environment variables (names only, never values) ===\n";
foreach (['VERCEL', 'VERCEL_ENV', 'VERCEL_URL',
          'DUETCS_DB_HOST', 'DUETCS_DB_PORT', 'DUETCS_DB_NAME',
          'DUETCS_DB_USER', 'DUETCS_DB_PASS', 'DUETCS_DB_SSL',
          'DUETCS_DEBUG'] as $k) {
    $via = [];
    if (getenv($k) !== false)  $via[] = 'getenv';
    if (isset($_SERVER[$k]))   $via[] = '$_SERVER';
    if (isset($_ENV[$k]))      $via[] = '$_ENV';
    printf("  %-18s %s\n", $k, $via ? 'set (via ' . implode(', ', $via) . ')' : 'NOT SET');
}

echo "\n=== database ===\n";
// config.php only defines constants, it opens no connection, so this is safe
// even when the database is unreachable.
require $root . '/config/config.php';
printf("  host configured : %s\n", DB_HOST === 'localhost'
    ? 'localhost  <-- NOT CONFIGURED (env vars did not arrive)'
    : 'yes, a remote host');
printf("  port            : %d\n", DB_PORT);
printf("  database        : %s\n", DB_NAME);
printf("  user            : %s\n", DB_USER);
printf("  password        : %s\n", DB_PASS === '' ? '(empty)' : '(set, ' . strlen(DB_PASS) . ' chars)');
printf("  tls             : %s\n", DB_SSL ? 'on' : 'off');

// Connect directly rather than through db(), which exits with its own error
// page on failure and would cut this report short.
try {
    $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 10];
    if (DB_SSL) {
        $opts[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
    $t0  = microtime(true);
    $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT
        . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $opts);
    printf("  connection      : OK (%.0f ms)\n", (microtime(true) - $t0) * 1000);
    echo "\n  tables:\n";
    foreach (['users', 'committee_members', 'events', 'activities',
              'gallery_photos', 'sessions'] as $t) {
        try {
            $n = (int) $pdo->query('SELECT COUNT(*) FROM `' . $t . '`')->fetchColumn();
            printf("    %-20s %d rows\n", $t, $n);
        } catch (Throwable $e) {
            printf("    %-20s MISSING\n", $t);
        }
    }
    echo "\n  If every table is listed, the site has everything it needs.\n";
} catch (Throwable $e) {
    echo "  connection      : FAILED\n";
    echo "  reason          : " . $e->getMessage() . "\n";
}
