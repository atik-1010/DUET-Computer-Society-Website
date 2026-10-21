<?php
/**
 * DUET Computer Society — front controller.
 *
 * Every request enters here. The first path segment selects a controller from a
 * whitelist; remaining segments are passed to it as $params.
 *
 *   index.php?p=events/upcoming   ->  controllers/events.php   $params = ['upcoming']
 *   index.php?p=blog/my-post      ->  controllers/blog.php     $params = ['my-post']
 */

require __DIR__ . '/config/config.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/app/helpers.php';
require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/components.php';

startSession();

// ---- Parse the route ----------------------------------------------------
$raw   = (string) ($_GET['p'] ?? '');

// A traversal attempt is refused outright rather than rewritten: stripping
// ".." from "...." would just produce ".." again.
if (str_contains($raw, '..')) {
    notFound();
}
// Dots are kept so that /media/<folder>/<file>.<ext> can carry a real
// filename. Nothing else needs them: the first segment is matched against the
// whitelist below, and the remaining segments reach controllers that either
// bind them into a query or validate their shape themselves.
$raw   = preg_replace('#[^A-Za-z0-9/_.\-]#', '', $raw);   // drop anything unexpected
$route = trim($raw, '/');
$GLOBALS['ROUTE'] = $route;

$segments   = $route === '' ? [] : explode('/', $route);
$controller = $segments[0] ?? '';
$params     = array_slice($segments, 1);

if ($controller === '') {
    $controller = 'home';
}

// ---- Whitelist of controllers ------------------------------------------
$routes = [
    'home'         => 'home',
    'about'        => 'about',
    'committee'    => 'committee',
    'events'       => 'events',
    'event'        => 'events',        // detail pages share the controller
    'activities'   => 'activities',
    'activity'     => 'activities',
    'competitions' => 'competitions',
    'competition'  => 'competitions',
    'projects'     => 'projects',
    'project'      => 'projects',
    'blog'         => 'blog',
    'gallery'      => 'gallery',
    'resources'    => 'resources',
    'membership'   => 'membership',
    'certificates' => 'certificates',
    'contact'      => 'contact',
    'search'       => 'search',
    'login'        => 'account',
    'register'     => 'account',
    'logout'       => 'account',
    'dashboard'    => 'account',
    'profile'      => 'account',
    'admin'        => 'admin',
    'media'        => 'media',       // files held in private Blob storage
];

if (!isset($routes[$controller])) {
    notFound();
}

// Detail routes are singular; tell the controller which form was used.
$GLOBALS['ROUTE_VERB'] = $controller;

$file = BASE_PATH . '/app/controllers/' . $routes[$controller] . '.php';
if (!is_file($file)) {
    notFound();
}
require $file;
