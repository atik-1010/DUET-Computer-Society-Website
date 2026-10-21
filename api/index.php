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
require dirname(__DIR__) . '/index.php';
