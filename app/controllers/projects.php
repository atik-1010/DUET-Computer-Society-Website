<?php
/** Student project showcase. */
$verb = $GLOBALS['ROUTE_VERB'];

if ($verb === 'project') {
    $slug = $params[0] ?? '';
    $pr = fetchOne('SELECT * FROM projects WHERE slug = ?', [$slug]);
    if (!$pr) {
        notFound();
    }
    page('project-detail', [
        'title'       => $pr['title'],
        'description' => $pr['summary'] ?: excerpt($pr['content'], 160),
        'project'     => $pr,
        'related'     => fetchAll('SELECT * FROM projects WHERE id <> ? ORDER BY is_featured DESC, id DESC LIMIT 3', [$pr['id']]),
    ]);
    return;
}

$cat = get('category', 'all');
$cats = [];
foreach (fetchAll('SELECT category, COUNT(*) c FROM projects GROUP BY category ORDER BY category') as $r) {
    $cats[$r['category']] = (int) $r['c'];
}

$where = ($cat !== 'all' && isset($cats[$cat])) ? 'WHERE category = :c' : '';
$bind  = $where ? ['c' => $cat] : [];

page('projects', [
    'title'       => 'Projects',
    'description' => 'Software and hardware built by DUETCS members — several of them running on campus today.',
    'subtitle'    => 'Built by members, several of them running on campus right now.',
    'projects'    => fetchAll("SELECT * FROM projects $where ORDER BY is_featured DESC, year DESC, id DESC", $bind),
    'cats'        => $cats,
    'cat'         => $cat,
]);
