<?php
/**
 * DUET Computer Society (DUETCS)
 * Global configuration
 */

// ---------- Environment ----------
/**
 * Read a deployment environment variable.
 *
 * getenv() on its own is not dependable across hosts: whether a variable lands
 * in the process environment, in $_SERVER or in $_ENV depends on the SAPI and
 * on the variables_order ini setting, and a serverless runtime that starts PHP
 * behind its own wrapper may forward the deployment's variables through only
 * one of the three. Checking all of them is what lets one config file work on
 * XAMPP, in Docker and on Vercel without edits.
 *
 * A variable that is set but empty wins over the default, so an intentionally
 * blank password (the XAMPP root default) still behaves as a blank password.
 */
if (!function_exists('envVar')) {
    function envVar(string $key, ?string $default = null): ?string
    {
        $found = getenv($key);
        if ($found === false) {
            $found = $_SERVER[$key] ?? $_ENV[$key] ?? false;
        }
        return $found === false ? $default : (string) $found;
    }
}

// ---------- Runtime environment ----------
// Vercel, like any serverless host, gives each request a fresh instance with a
// read-only filesystem. Three things have to behave differently there: the
// entrypoint lives in /api instead of the web root, sessions cannot be files,
// and uploads cannot be written at all.
define('SERVERLESS', envVar('VERCEL') !== null || envVar('VERCEL_ENV') !== null || envVar('AWS_LAMBDA_FUNCTION_NAME') !== null);

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
define('DB_HOST', envVar('DUETCS_DB_HOST') ?: 'localhost');
define('DB_NAME', envVar('DUETCS_DB_NAME') ?: 'duetcs');
define('DB_USER', envVar('DUETCS_DB_USER') ?: 'root');
define('DB_PASS', envVar('DUETCS_DB_PASS', ''));
define('DB_CHARSET', 'utf8mb4');
// Managed MySQL providers (Aiven, TiDB Cloud, Railway, Clever Cloud) hand out a
// non-standard port and require an encrypted connection, so both have to be
// configurable. DUETCS_DB_SSL=1 turns TLS on without shipping a CA bundle;
// set DUETCS_DB_SSL_CA to a .pem path instead to verify the server certificate.
define('DB_PORT', (int) (envVar('DUETCS_DB_PORT') ?: 3306));
define('DB_SSL_CA', (string) (envVar('DUETCS_DB_SSL_CA') ?: ''));
define('DB_SSL', DB_SSL_CA !== '' || (bool) (int) (envVar('DUETCS_DB_SSL') ?: 0));

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
// Serverless instances have a read-only filesystem, so uploads cannot be saved
// to uploads/ there. Files already committed to uploads/ are still served.
define('UPLOADS_WRITABLE', !SERVERLESS);

// Vercel Blob. BLOB_READ_WRITE_TOKEN is set on the deployment automatically
// once a Blob store is linked to the project; with it, uploads work on the
// read-only filesystem by going to the store and keeping the returned URL in
// the database. Locally the token is absent and uploads/ is used as before.
define('BLOB_TOKEN', (string) (envVar('BLOB_READ_WRITE_TOKEN') ?: ''));
define('BLOB_ENABLED', BLOB_TOKEN !== '');

// How a stored value says "this lives in the Blob store" rather than being a
// filename committed under uploads/. upload() turns it into a /media/ link.
define('BLOB_MARKER', 'blob:');

// Folders holding other people's personal documents. The media route serves
// these only to an administrator, or to the member the file belongs to --
// everything else there is site artwork and is open to all, exactly as it is
// on the pages that display it.
define('BLOB_PRIVATE_FOLDERS', ['payments', 'members']);

// ---------- Membership ----------
define('MEMBERSHIP_FEE', 300);           // BDT per year
define('MEMBERSHIP_FEE_LIFETIME', 1500); // BDT

// ---------- Session / errors ----------
// Never show errors on a public deployment: config/database.php prints the
// raw PDO message on failure, which would publish the database host and user
// to any visitor. Set DUETCS_DEBUG=1 in the host's environment to re-enable.
$__debug = envVar('DUETCS_DEBUG');
define('DEBUG', $__debug !== null ? (bool) (int) $__debug : !SERVERLESS);
// Show errors to the visitor only in debug mode -- but never silence them.
// error_reporting(0) means a fatal renders a blank white page and writes
// nothing anywhere, which is the hardest possible failure to diagnose on a
// host whose filesystem you cannot inspect. Logging always stays on, so the
// host's own log (Vercel's runtime logs) captures what happened.
ini_set('display_errors', DEBUG ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Dhaka');
