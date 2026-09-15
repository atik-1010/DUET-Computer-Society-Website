<?php
/** Competitions: IUPC, intra-DUET, hackathons and everything else. */
$verb = $GLOBALS['ROUTE_VERB'];

$types = [
    'iupc'      => ['IUPC',                'Our national inter-university programming contest.'],
    'intra'     => ['Intra DUET Contest',  'The campus championship, and the route into our ICPC teams.'],
    'hackathon' => ['Hackathon',           'Thirty-six hours, an empty repository, and a working demo at the end.'],
    'other'     => ['Other Contests',      'CTFs, project showcases and the events we send teams to.'],
];

if ($verb === 'competition') {
    $slug = $params[0] ?? '';
    $c = fetchOne('SELECT * FROM competitions WHERE slug = ?', [$slug]);
    if (!$c) {
        notFound();
    }
    page('competition-detail', [
        'title'       => $c['title'],
        'description' => $c['summary'] ?: excerpt($c['content'], 160),
        'competition' => $c,
        'typeLabel'   => $types[$c['type']][0] ?? ucfirst($c['type']),
        'related'     => fetchAll(
            'SELECT * FROM competitions WHERE type = ? AND id <> ? ORDER BY event_date DESC LIMIT 3',
            [$c['type'], $c['id']]
        ),
    ]);
    return;
}

$type = $params[0] ?? 'all';
if ($type !== 'all' && !isset($types[$type])) {
    notFound();
}

$where = $type === 'all' ? '' : 'WHERE type = :t';
$bind  = $type === 'all' ? [] : ['t' => $type];

$counts = [];
foreach (fetchAll('SELECT type, COUNT(*) c FROM competitions GROUP BY type') as $r) {
    $counts[$r['type']] = (int) $r['c'];
}
$counts['all'] = array_sum($counts);

[$title, $sub] = $type === 'all'
    ? ['Competitions', 'Every contest DUETCS hosts, runs internally, or sends teams to.']
    : $types[$type];

page('competitions', [
    'title'        => $title,
    'description'  => $sub,
    'subtitle'     => $sub,
    'competitions' => fetchAll(
        "SELECT * FROM competitions $where ORDER BY FIELD(status,'ongoing','upcoming','past'), event_date DESC",
        $bind
    ),
    'type'   => $type,
    'types'  => $types,
    'counts' => $counts,
]);
