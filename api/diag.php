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
