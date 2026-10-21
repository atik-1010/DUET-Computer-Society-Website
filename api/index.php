<?php
/**
 * Vercel serverless entrypoint.
 *
 * Vercel requires the PHP function to live under /api. The real front
 * controller stays at the project root so that Apache/XAMPP and Docker keep
 * working unchanged — this file only hands the request over to it.
 *
 * Routing is done by vercel.json, which rewrites every non-static request to
 * this file with the original path in ?p=, exactly the shape index.php expects.
 */

// Temporary deployment diagnostic, reachable at /_diag.
//
// It hangs off the front controller's own ?p= value rather than a vercel.json
// route of its own: the platform was observed to skip the routes listed before
// its filesystem phase, so only the catch-all rewrite can be relied on. Going
// through ?p= means this works whichever way the route table is resolved.
if (($_GET['p'] ?? '') === '_diag') {
    require __DIR__ . '/diag.php';
    exit;
}

require dirname(__DIR__) . '/index.php';
