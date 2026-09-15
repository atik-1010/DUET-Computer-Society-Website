<?php
/** Curated learning resources, grouped by category. */
$categories = fetchAll('SELECT * FROM resource_categories ORDER BY sort_order');
$slug = $params[0] ?? '';

$counts = [];
foreach (fetchAll('SELECT category, COUNT(*) c FROM resources GROUP BY category') as $r) {
    $counts[$r['category']] = (int) $r['c'];
}

if ($slug === '') {
    page('resources', [
        'title'       => 'Resources',
        'description' => 'A curated, free library covering programming, development, AI, security and careers.',
        'subtitle'    => 'Everything below is free, vetted by our members, and grouped by where you are going.',
        'categories'  => $categories,
        'counts'      => $counts,
        'total'       => array_sum($counts),
    ]);
    return;
}

$cat = fetchOne('SELECT * FROM resource_categories WHERE slug = ?', [$slug]);
if (!$cat) {
    notFound();
}

$level = get('level', 'all');
$where = 'WHERE category = :c';
$bind  = ['c' => $slug];
if (in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
    $where .= ' AND level = :l';
    $bind['l'] = $level;
}

page('resource-category', [
    'title'       => $cat['name'] . ' Resources',
    'description' => $cat['description'] ?: SITE_DESC,
    'category'    => $cat,
    'categories'  => $categories,
    'counts'      => $counts,
    'resources'   => fetchAll("SELECT * FROM resources $where ORDER BY level, sort_order", $bind),
    'level'       => $level,
]);
