<?php
/** About section — four database-backed static pages. */
$map = [
    ''             => ['about',        'About DUETCS'],
    'history'      => ['history',      'History'],
    'mission'      => ['mission',      'Mission & Vision'],
    'constitution' => ['constitution', 'Constitution & Guidelines'],
];

$key = $params[0] ?? '';
if (!isset($map[$key])) {
    notFound();
}
[$slug, $fallbackTitle] = $map[$key];

$row = fetchOne('SELECT * FROM pages WHERE slug = ?', [$slug]);
if (!$row) {
    $row = ['title' => $fallbackTitle, 'subtitle' => '', 'content' => '<p>This page has not been written yet.</p>', 'updated_at' => null];
}

$crumbs = ['About' => 'about'];
if ($key !== '') {
    $crumbs[$row['title']] = null;
}

page('about', [
    'title'       => $row['title'],
    'description' => $row['subtitle'] ?: SITE_DESC,
    'page'        => $row,
    'crumbs'      => $crumbs,
    'active'      => $key,
    'nav'         => $map,
    'stats'       => [
        'Members'      => (int) setting('stat_members', 900),
        'Events held'  => (int) setting('stat_events', 120),
        'Contests run' => (int) setting('stat_contests', 38),
        'Years active' => (int) setting('stat_years', 15),
    ],
]);
