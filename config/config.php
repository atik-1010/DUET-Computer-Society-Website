<?php
/**
 * DUET Computer Society (DUETCS)
 * Global configuration
 */

// ---------- Database ----------
// Values default to a standard XAMPP setup. They can be overridden with
// environment variables, which is how docker-compose.yml points the app at
// its own MySQL container without editing this file.
define('DB_HOST', getenv('DUETCS_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DUETCS_DB_NAME') ?: 'duetcs');
define('DB_USER', getenv('DUETCS_DB_USER') ?: 'root');
define('DB_PASS', getenv('DUETCS_DB_PASS') !== false ? getenv('DUETCS_DB_PASS') : '');
define('DB_CHARSET', 'utf8mb4');

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
$__dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$__dir = ($__dir === '/' || $__dir === '.') ? '' : rtrim($__dir, '/');
$__scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('BASE_URL', $__scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $__dir);
define('BASE_PATH', dirname(__DIR__));

// Set to true ONLY if Apache mod_rewrite is enabled (.htaccess is included).
define('PRETTY_URLS', false);

// ---------- Uploads ----------
define('UPLOAD_DIR', BASE_PATH . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');
define('MAX_UPLOAD', 3 * 1024 * 1024); // 3 MB

// ---------- Membership ----------
define('MEMBERSHIP_FEE', 300);           // BDT per year
define('MEMBERSHIP_FEE_LIFETIME', 1500); // BDT

// ---------- Session / errors ----------
define('DEBUG', true);
if (DEBUG) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
date_default_timezone_set('Asia/Dhaka');
