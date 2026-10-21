<?php
/**
 * Vercel serverless entrypoint.
 *
 * Vercel requires the PHP function to live under /api. The real front
 * controller stays at the project root so that Apache/XAMPP and Docker keep
 * working unchanged — this file only hands the request over to it.
 *
 * vercel.json rewrites every request to this file with the original path in
 * ?p=, exactly the shape index.php expects.
 */

/**
 * Serve one of the project's own static files, then stop.
 *
 * Vercel does not publish this repository's files through its filesystem
 * phase, and the catch-all rewrite hands every path to this function as ?p=,
 * so the PHP development server never sees a path it could serve itself. The
 * files are in the function bundle (vercel.json includeFiles), so they are
 * served from here instead — otherwise every stylesheet and photo 404s.
 */
function serveProjectAsset(string $rel): void
{
    $types = [
        'css'  => 'text/css',       'js'    => 'text/javascript',
        'svg'  => 'image/svg+xml',  'png'   => 'image/png',
        'jpg'  => 'image/jpeg',     'jpeg'  => 'image/jpeg',
        'webp' => 'image/webp',     'gif'   => 'image/gif',
        'ico'  => 'image/x-icon',   'woff'  => 'font/woff',
        'woff2' => 'font/woff2',    'ttf'   => 'font/ttf',
        'map'  => 'application/json',
    ];

    $root = dirname(__DIR__);
    $ext  = strtolower(pathinfo($rel, PATHINFO_EXTENSION));
    $path = realpath($root . '/' . $rel);

    // Only ever serve from these two directories. realpath() has already
    // resolved any ".." by this point, so comparing the resolved prefix is
    // what keeps a crafted path from reaching config/ or app/.
    $inside = false;
    foreach (['assets', 'uploads'] as $dir) {
        $base = realpath($root . '/' . $dir);
        if ($base !== false && $path !== false && str_starts_with($path, $base . DIRECTORY_SEPARATOR)) {
            $inside = true;
            break;
        }
    }

    if (!$inside || !isset($types[$ext]) || !is_file($path)) {
        http_response_code(404);
        exit;
    }

    header('Content-Type: ' . $types[$ext]);
    header('Content-Length: ' . filesize($path));
    // These change only when the project is redeployed, so let the CDN and the
    // browser keep them rather than waking the function for every image.
    header('Cache-Control: public, max-age=31536000, immutable');
    readfile($path);
    exit;
}

$requested = (string) ($_GET['p'] ?? '');

if (preg_match('#^(?:assets|uploads)/#', $requested)) {
    serveProjectAsset($requested);   // serves the file, or 404s; never returns
}

require dirname(__DIR__) . '/index.php';
