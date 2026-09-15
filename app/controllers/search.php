<?php
/** Site-wide search across events, activities, competitions, projects and posts. */
$term = trim((string) get('q', ''));
$results = [];

if (mb_strlen($term) >= 2) {
    $like = '%' . $term . '%';

    $sources = [
        'Events'       => ['events',       'event',       'title', 'summary',  'slug'],
        'Activities'   => ['activities',   'activity',    'title', 'summary',  'slug'],
        'Competitions' => ['competitions', 'competition', 'title', 'summary',  'slug'],
        'Projects'     => ['projects',     'project',     'title', 'summary',  'slug'],
        'Articles'     => ['posts',        'blog',        'title', 'excerpt',  'slug'],
    ];

    foreach ($sources as $label => [$table, $route, $titleCol, $descCol, $slugCol]) {
        $extra = $table === 'posts' ? "AND status = 'published'" : '';
        $rows = fetchAll(
            "SELECT $titleCol AS title, $descCol AS summary, $slugCol AS slug
             FROM `$table`
             WHERE ($titleCol LIKE :a OR $descCol LIKE :b) $extra
             ORDER BY $titleCol LIMIT 6",
            ['a' => $like, 'b' => $like]
        );
        if ($rows) {
            $results[$label] = ['route' => $route, 'rows' => $rows];
        }
    }
}

page('search', [
    'title'       => $term !== '' ? 'Search: ' . $term : 'Search',
    'description' => 'Search everything on the DUETCS website.',
    'term'        => $term,
    'results'     => $results,
    'count'       => array_sum(array_map(fn($g) => count($g['rows']), $results)),
]);
