<?php
/** Event listing and detail. */
$verb = $GLOBALS['ROUTE_VERB'];

// ---- detail: /event/{slug} ----
if ($verb === 'event') {
    $slug = $params[0] ?? '';
    $ev = fetchOne('SELECT * FROM events WHERE slug = ?', [$slug]);
    if (!$ev) {
        notFound();
    }
    page('event-detail', [
        'title'       => $ev['title'],
        'description' => $ev['summary'] ?: excerpt($ev['content'], 160),
        'event'       => $ev,
        'related'     => fetchAll(
            'SELECT * FROM events WHERE id <> ? AND status = ? ORDER BY start_datetime DESC LIMIT 3',
            [$ev['id'], $ev['status']]
        ),
    ]);
    return;
}

// ---- listing: /events[/status] ----
$filter = $params[0] ?? 'all';
$allowed = ['all', 'upcoming', 'ongoing', 'past'];
if (!in_array($filter, $allowed, true)) {
    notFound();
}

$where  = $filter === 'all' ? '' : 'WHERE status = :st';
$bind   = $filter === 'all' ? [] : ['st' => $filter];
$order  = $filter === 'past' ? 'start_datetime DESC' : 'start_datetime ASC';

$perPage = 9;
$page    = max(1, (int) get('page', 1));
$total   = (int) fetchValue("SELECT COUNT(*) FROM events $where", $bind, 0);
$pages   = max(1, (int) ceil($total / $perPage));
$page    = min($page, $pages);
$offset  = ($page - 1) * $perPage;

$events = fetchAll("SELECT * FROM events $where ORDER BY $order LIMIT $perPage OFFSET $offset", $bind);

$counts = [];
foreach (fetchAll('SELECT status, COUNT(*) c FROM events GROUP BY status') as $r) {
    $counts[$r['status']] = (int) $r['c'];
}
$counts['all'] = array_sum($counts);

$labels = [
    'all'      => ['Events',          'Everything DUETCS has run, is running, and is about to run.'],
    'upcoming' => ['Upcoming Events', 'Register now — these have not happened yet.'],
    'ongoing'  => ['Ongoing Events',  'Running right now. Most are still open to join.'],
    'past'     => ['Past Events',     'The archive — recordings, results and write-ups.'],
];

page('events', [
    'title'       => $labels[$filter][0],
    'description' => $labels[$filter][1],
    'subtitle'    => $labels[$filter][1],
    'events'      => $events,
    'filter'      => $filter,
    'counts'      => $counts,
    'page'        => $page,
    'pages'       => $pages,
]);
