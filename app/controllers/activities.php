<?php
/** Activity wings, their sessions, and single-activity pages. */
$verb = $GLOBALS['ROUTE_VERB'];

if ($verb === 'activity') {
    $slug = $params[0] ?? '';
    $a = fetchOne('SELECT * FROM activities WHERE slug = ?', [$slug]);
    if (!$a) {
        notFound();
    }
    $cat = fetchOne('SELECT * FROM activity_categories WHERE slug = ?', [$a['category']]);
    page('activity-detail', [
        'title'       => $a['title'],
        'description' => $a['summary'] ?: excerpt($a['content'], 160),
        'activity'    => $a,
        'category'    => $cat,
        'related'     => fetchAll(
            'SELECT * FROM activities WHERE category = ? AND id <> ? ORDER BY id DESC LIMIT 3',
            [$a['category'], $a['id']]
        ),
    ]);
    return;
}

$categories = fetchAll('SELECT * FROM activity_categories ORDER BY sort_order');
$slug = $params[0] ?? '';

// Overview of every wing
if ($slug === '') {
    $counts = [];
    foreach (fetchAll('SELECT category, COUNT(*) c FROM activities GROUP BY category') as $r) {
        $counts[$r['category']] = (int) $r['c'];
    }
    page('activities', [
        'title'       => 'Activities',
        'description' => 'Six wings covering programming, AI, security, development, workshops and seminars.',
        'subtitle'    => 'Six wings, each with its own lead, its own calendar and its own way in.',
        'categories'  => $categories,
        'counts'      => $counts,
        'featured'    => fetchAll('SELECT * FROM activities WHERE is_featured = 1 ORDER BY id DESC LIMIT 3'),
    ]);
    return;
}

// One wing
$cat = fetchOne('SELECT * FROM activity_categories WHERE slug = ?', [$slug]);
if (!$cat) {
    notFound();
}

page('activity-category', [
    'title'       => $cat['name'],
    'description' => $cat['tagline'] ?: SITE_DESC,
    'category'    => $cat,
    'categories'  => $categories,
    'activities'  => fetchAll('SELECT * FROM activities WHERE category = ? ORDER BY is_featured DESC, activity_date DESC', [$slug]),
]);
