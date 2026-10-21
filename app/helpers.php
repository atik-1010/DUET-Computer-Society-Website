<?php
/**
 * View + request helpers.
 */

/** Escape for HTML output. Use on EVERY dynamic value in a template. */
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Build a site URL for a route path, e.g. url('about/history'). */
function url(string $path = ''): string
{
    $path = trim($path, '/');
    if ($path === '') {
        return BASE_URL . '/';
    }
    return PRETTY_URLS
        ? BASE_URL . '/' . $path
        : BASE_URL . '/index.php?p=' . $path;
}

/** URL for an asset under /assets. */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Resolve a stored media reference to a browsable URL.
 * Accepts an absolute URL, a path under /assets, or a filename inside
 * /uploads/$folder. Returns '' when nothing usable exists, so templates can
 * fall back to a generated gradient instead of a broken image.
 */
function upload(?string $file, string $folder = ''): string
{
    $file = trim((string) $file);
    if ($file === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $file)) {
        return $file;
    }
    if (str_starts_with($file, 'assets/')) {
        return BASE_URL . '/' . $file;
    }
    $rel = ($folder !== '' ? trim($folder, '/') . '/' : '') . basename($file);
    return is_file(UPLOAD_DIR . '/' . $rel) ? UPLOAD_URL . '/' . $rel : '';
}

/** A stable hue (0-359) derived from any string — used for generated artwork. */
function hueFor(string $seed): int
{
    return (int) (hexdec(substr(md5($seed), 0, 6)) % 360);
}

/**
 * A deterministic gradient for a card that has no image. Two entries with
 * different titles always get visibly different artwork.
 */
function gradientFor(string $seed): string
{
    $h = hueFor($seed);
    $h2 = ($h + 48) % 360;
    return sprintf(
        'background-image:linear-gradient(135deg, hsl(%d 72%% 58%%), hsl(%d 78%% 46%%));',
        $h, $h2
    );
}

/** Up to two initials from a person's or item's name. */
function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $parts = array_values(array_filter($parts, fn($w) => $w !== '' && ctype_alpha($w[0])));
    if (!$parts) {
        return '?';
    }
    $first = mb_strtoupper(mb_substr($parts[0], 0, 1));
    $last  = count($parts) > 1 ? mb_strtoupper(mb_substr(end($parts), 0, 1)) : '';
    return $first . $last;
}

/** Redirect to a route and stop. */
function redirect(string $path): void
{
    header('Location: ' . (preg_match('#^https?://#i', $path) ? $path : url($path)));
    exit;
}

/** The current route path, e.g. "about/history". */
function currentRoute(): string
{
    return trim($GLOBALS['ROUTE'] ?? '', '/');
}

/** True when $path is the active route (or a parent of it). */
function isActive(string $path, bool $exact = false): bool
{
    $cur  = currentRoute();
    $path = trim($path, '/');
    if ($path === '') {
        return $cur === '';
    }
    return $exact ? $cur === $path : ($cur === $path || str_starts_with($cur, $path . '/'));
}

/** Flash message helpers. */
function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
}

function takeFlash(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/** CSRF token for forms. */
function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfField(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrfToken()) . '">';
}

/** Verify the posted CSRF token; aborts on mismatch. */
function csrfCheck(): void
{
    $sent = $_POST['_token'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(403);
        exit('Security token expired. Please go back and submit the form again.');
    }
}

/** Trimmed POST value. */
function post(string $key, $default = '')
{
    $v = $_POST[$key] ?? $default;
    return is_string($v) ? trim($v) : $v;
}

/** Trimmed GET value. */
function get(string $key, $default = '')
{
    $v = $_GET[$key] ?? $default;
    return is_string($v) ? trim($v) : $v;
}

/** URL-safe slug. */
function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('~[^-\w]+~', '', strtolower(trim($text, '-')));
    return $text !== '' ? $text : 'item';
}

/** "12 Mar 2026" */
function fdate(?string $date, string $format = 'd M Y'): string
{
    if (!$date || $date === '0000-00-00' || str_starts_with($date, '0000')) {
        return '—';
    }
    return date($format, strtotime($date));
}

/** "3 days ago" */
function ago(?string $date): string
{
    if (!$date) return '—';
    $diff = time() - strtotime($date);
    if ($diff < 60)     return 'just now';
    if ($diff < 3600)   return floor($diff / 60) . 'm ago';
    if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return fdate($date);
}

/** Trim text to a word boundary. */
function excerpt(?string $text, int $limit = 140): string
{
    $text = trim(strip_tags((string) $text));
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    $cut = mb_substr($text, 0, $limit);
    $sp  = mb_strrpos($cut, ' ');
    return rtrim($sp ? mb_substr($cut, 0, $sp) : $cut, ' ,.;:') . '…';
}

/** Money in BDT. */
function taka($amount): string
{
    return '৳' . number_format((float) $amount, 0);
}

/** Read a value from the settings table (cached per request). */
function setting(string $key, $default = '')
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (fetchAll('SELECT `key`, `value` FROM settings') as $r) {
            $cache[$r['key']] = $r['value'];
        }
    }
    return $cache[$key] ?? $default;
}

/**
 * Store an uploaded image safely: validates the real MIME type, size and
 * extension, then writes it under /uploads/$folder with a random name.
 * Returns the stored filename, or null when no file was sent.
 */
function saveUpload(string $field, string $folder, ?string &$error = null): ?string
{
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    // A serverless deployment has a read-only filesystem. With a Blob store
    // linked the file goes there instead (below); without one there is nowhere
    // to put it, so say so plainly rather than letting mkdir() and
    // move_uploaded_file() emit warnings and look like a generic save error.
    if (!UPLOADS_WRITABLE && !BLOB_ENABLED) {
        $error = 'This deployment cannot store new uploads: its filesystem is read-only '
               . 'and no file storage is configured. Commit the image to the uploads/ '
               . 'folder in the repository, or link a Vercel Blob store to the project.';
        return null;
    }

    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed. Please try a smaller file.';
        return null;
    }
    if ($f['size'] > MAX_UPLOAD) {
        $error = 'File is larger than ' . (MAX_UPLOAD / 1024 / 1024) . ' MB.';
        return null;
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!isset($allowed[$mime])) {
        $error = 'Only JPG, PNG, WEBP or GIF images are allowed.';
        return null;
    }

    $name = bin2hex(random_bytes(8)) . '-' . time() . '.' . $allowed[$mime];

    // Where the filesystem cannot be written, the bytes go to Blob storage and
    // what is returned — and therefore stored in the database — is a URL.
    // upload() passes an absolute URL straight through, so nothing downstream
    // has to know which of the two a given record uses.
    if (!UPLOADS_WRITABLE) {
        require_once BASE_PATH . '/app/blob.php';
        return blobPut(trim($folder, '/') . '/' . $name, $f['tmp_name'], $mime, $error);
    }

    $dir = UPLOAD_DIR . '/' . trim($folder, '/');
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        $error = 'Could not create the upload folder on the server.';
        return null;
    }
    if (!move_uploaded_file($f['tmp_name'], $dir . '/' . $name)) {
        $error = 'Could not save the file on the server.';
        return null;
    }
    return $name;
}

/** Delete a previously uploaded file. */
function deleteUpload(?string $file, string $folder): void
{
    if (!$file) return;

    // A stored value that is a URL lives in Blob storage, not on this disk.
    if (preg_match('#^https?://#i', $file)) {
        require_once BASE_PATH . '/app/blob.php';
        blobDelete($file);
        return;
    }

    $path = UPLOAD_DIR . '/' . trim($folder, '/') . '/' . basename($file);
    if (is_file($path)) {
        @unlink($path);
    }
}

/** Render a view file with $data extracted into scope. */
function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $file = BASE_PATH . '/views/' . $template . '.php';
    if (!is_file($file)) {
        http_response_code(500);
        exit('View not found: ' . e($template));
    }
    require $file;
}

/** Render a public page inside the site layout. */
function page(string $template, array $data = []): void
{
    $GLOBALS['PAGE_DATA'] = $data;
    require BASE_PATH . '/views/layouts/header.php';
    view('pages/' . $template, $data);
    require BASE_PATH . '/views/layouts/footer.php';
}

/** Show the 404 page. */
function notFound(): void
{
    http_response_code(404);
    page('404', ['title' => 'Page Not Found']);
    exit;
}

/** Icon set (inline SVG so there is no external icon dependency). */
function icon(string $name, string $class = 'icon'): string
{
    $p = [
        'code'      => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
        'cpu'       => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 2v2M15 2v2M9 20v2M15 20v2M2 9h2M2 15h2M20 9h2M20 15h2"/>',
        'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'layers'    => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'trophy'    => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
        'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'users'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'image'     => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
        'book'      => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
        'award'     => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
        'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
        'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'arrow'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'arrowLeft' => '<path d="M19 12H5M12 19l-7-7 7-7"/>',
        'check'     => '<path d="M20 6 9 17l-5-5"/>',
        'clock'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'search'    => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
        'sun'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>',
        'moon'      => '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>',
        'menu'      => '<path d="M3 12h18M3 6h18M3 18h18"/>',
        'close'     => '<path d="M18 6 6 18M6 6l12 12"/>',
        'play'      => '<polygon points="5 3 19 12 5 21 5 3"/>',
        'external'  => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><path d="M10 14 21 3"/>',
        'github'    => '<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>',
        'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
        'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
        'wallet'    => '<path d="M20 12V8H6a2 2 0 0 1 0-4h12v4"/><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/>',
        'file'      => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
        'logout'    => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><path d="M21 12H9"/>',
        'dashboard' => '<rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/>',
        'settings'  => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.2.61.75 1.03 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
        'edit'      => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/>',
        'trash'     => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'plus'      => '<path d="M12 5v14M5 12h14"/>',
        'sparkle'   => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8"/>',
        'target'    => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'eye'       => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
    ];
    $body = $p[$name] ?? $p['code'];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" '
         . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
}
