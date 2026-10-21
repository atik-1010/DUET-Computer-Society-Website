<?php
/**
 * DUET Computer Society (DUETCS)
 * Global configuration
 */

// ---------- Runtime environment ----------
// Vercel, like any serverless host, gives each request a fresh instance with a
// read-only filesystem. Three things have to behave differently there: the
// entrypoint lives in /api instead of the web root, sessions cannot be files,
// and uploads cannot be written at all.
define('SERVERLESS', getenv('VERCEL') !== false || getenv('AWS_LAMBDA_FUNCTION_NAME') !== false);

// Vercel terminates TLS at its edge and forwards the request over http, so
// $_SERVER['HTTPS'] is empty even though the visitor is on https. Trusting the
// forwarded header is what keeps BASE_URL and the session cookie's Secure flag
// correct; without it the app builds http:// asset URLs on an https:// page
// and the browser blocks them as mixed content.
define('IS_HTTPS', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'));

// ---------- Database ----------
// Values default to a standard XAMPP setup. They can be overridden with
// environment variables, which is how docker-compose.yml points the app at
// its own MySQL container without editing this file.
define('DB_HOST', getenv('DUETCS_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DUETCS_DB_NAME') ?: 'duetcs');
define('DB_USER', getenv('DUETCS_DB_USER') ?: 'root');
define('DB_PASS', getenv('DUETCS_DB_PASS') !== false ? getenv('DUETCS_DB_PASS') : '');
define('DB_CHARSET', 'utf8mb4');
// Managed MySQL providers (Aiven, TiDB Cloud, Railway, Clever Cloud) hand out a
// non-standard port and require an encrypted connection, so both have to be
// configurable. DUETCS_DB_SSL=1 turns TLS on without shipping a CA bundle;
// set DUETCS_DB_SSL_CA to a .pem path instead to verify the server certificate.
define('DB_PORT', (int) (getenv('DUETCS_DB_PORT') ?: 3306));
define('DB_SSL_CA', (string) (getenv('DUETCS_DB_SSL_CA') ?: ''));
define('DB_SSL', DB_SSL_CA !== '' || (bool) (int) (getenv('DUETCS_DB_SSL') ?: 0));

// ---------- Site identity ----------
define('SITE_NAME',  'DUET Computer Society');
define('SITE_SHORT', 'DUETCS');
define('SITE_TAGLINE', 'Code. Create. Compete.');
define('SITE_DESC',  'The official computer society of Dhaka University of Engineering & Technology, Gazipur - building programmers, researchers and innovators.');
define('SITE_EMAIL', 'duetcs@duet.ac.bd');
define('SITE_PHONE', '+880 1738-587110');
define('SITE_ADDRESS', 'Dept. of CSE, DUET, Gazipur-1707, Bangladesh');

// ---------- URLs ----------
// Auto-detect the base URL so the project works from any folder in htdocs.
// On serverless the running script is /api/index.php, but the site is still
// served from the domain root, so the script's directory must be ignored --
// otherwise every generated link and asset URL picks up a stray /api prefix.
if (SERVERLESS) {
    $__dir = '';
} else {
    $__dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $__dir = ($__dir === '/' || $__dir === '.') ? '' : rtrim($__dir, '/');
}
define('BASE_URL', (IS_HTTPS ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $__dir);
define('BASE_PATH', dirname(__DIR__));

// Clean URLs require a rewrite in front of the app: Apache mod_rewrite via
// .htaccess locally, or the routes block in vercel.json on Vercel. Those
// routes are always part of the deployment, so they can be relied on there.
define('PRETTY_URLS', SERVERLESS);

// ---------- Uploads ----------
define('UPLOAD_DIR', BASE_PATH . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');
define('MAX_UPLOAD', 3 * 1024 * 1024); // 3 MB
// Serverless instances have a read-only filesystem, so new uploads cannot be
// stored. Files already committed to uploads/ are still served normally.
define('UPLOADS_WRITABLE', !SERVERLESS);

// ---------- Membership ----------
define('MEMBERSHIP_FEE', 300);           // BDT per year
define('MEMBERSHIP_FEE_LIFETIME', 1500); // BDT

// ---------- Session / errors ----------
// Never show errors on a public deployment: config/database.php prints the
// raw PDO message on failure, which would publish the database host and user
// to any visitor. Set DUETCS_DEBUG=1 in the host's environment to re-enable.
$__debug = getenv('DUETCS_DEBUG');
define('DEBUG', $__debug !== false ? (bool) (int) $__debug : !SERVERLESS);
if (DEBUG) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
date_default_timezone_set('Asia/Dhaka');
