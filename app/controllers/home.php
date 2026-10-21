<?php
/** Landing page. */
$now = date('Y-m-d H:i:s');

$upcoming = fetchAll(
    "SELECT * FROM events WHERE status IN ('upcoming','ongoing') ORDER BY start_datetime ASC LIMIT 3"
);
if (!$upcoming) {
    $upcoming = fetchAll('SELECT * FROM events ORDER BY start_datetime DESC LIMIT 3');
}

page('home', [
    'title'       => SITE_NAME,
    'description' => SITE_DESC,
    'categories'  => fetchAll('SELECT * FROM activity_categories ORDER BY sort_order'),
    'events'      => $upcoming,
    'competitions'=> fetchAll("SELECT * FROM competitions ORDER BY (status='upcoming') DESC, event_date DESC LIMIT 3"),
    'projects'    => fetchAll('SELECT * FROM projects ORDER BY is_featured DESC, id DESC LIMIT 3'),
    'posts'       => fetchAll("SELECT * FROM posts WHERE status='published' ORDER BY published_at DESC LIMIT 3"),
    'committee'   => fetchAll(
        'SELECT cm.* FROM committee_members cm
         JOIN committees c ON c.id = cm.committee_id
         WHERE c.is_current = 1 AND c.type = ?
         ORDER BY cm.sort_order LIMIT 4',
        ['executive']
    ),
    'plans'       => fetchAll('SELECT * FROM membership_plans WHERE is_active = 1 ORDER BY sort_order'),
]);
