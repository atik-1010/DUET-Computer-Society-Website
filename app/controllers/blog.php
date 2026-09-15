<?php
/** Blog listing and article pages. */
$slug = $params[0] ?? '';

if ($slug !== '') {
    $post = fetchOne("SELECT * FROM posts WHERE slug = ? AND status = 'published'", [$slug]);
    if (!$post) {
        notFound();
    }
    // Count the read once per session per article.
    $seen = $_SESSION['seen_posts'] ?? [];
    if (!in_array((int) $post['id'], $seen, true)) {
        q('UPDATE posts SET views = views + 1 WHERE id = ?', [$post['id']]);
        $seen[] = (int) $post['id'];
        $_SESSION['seen_posts'] = $seen;
        $post['views']++;
    }

    $tags = array_filter(array_map('trim', explode(',', (string) $post['tags'])));

    page('post-detail', [
        'title'       => $post['title'],
        'description' => $post['excerpt'] ?: excerpt($post['content'], 160),
        'post'        => $post,
        'tags'        => $tags,
        'related'     => fetchAll(
            "SELECT * FROM posts WHERE status='published' AND category = ? AND id <> ? ORDER BY published_at DESC LIMIT 3",
            [$post['category'], $post['id']]
        ),
        'popular'     => fetchAll("SELECT id, title, slug, published_at FROM posts WHERE status='published' ORDER BY views DESC LIMIT 5"),
    ]);
    return;
}

$cat = get('category', 'all');
$cats = [];
foreach (fetchAll("SELECT category, COUNT(*) c FROM posts WHERE status='published' GROUP BY category ORDER BY category") as $r) {
    $cats[$r['category']] = (int) $r['c'];
}

$where = "WHERE status='published'";
$bind  = [];
if ($cat !== 'all' && isset($cats[$cat])) {
    $where .= ' AND category = :c';
    $bind['c'] = $cat;
}

$perPage = 6;
$pageNo  = max(1, (int) get('page', 1));
$total   = (int) fetchValue("SELECT COUNT(*) FROM posts $where", $bind, 0);
$pages   = max(1, (int) ceil($total / $perPage));
$pageNo  = min($pageNo, $pages);
$offset  = ($pageNo - 1) * $perPage;

page('blog', [
    'title'       => 'Blog & Articles',
    'description' => 'Guides, retrospectives and honest advice written by DUETCS members.',
    'subtitle'    => 'Guides, retrospectives and honest advice, written by members.',
    'posts'       => fetchAll("SELECT * FROM posts $where ORDER BY published_at DESC LIMIT $perPage OFFSET $offset", $bind),
    'cats'        => $cats,
    'cat'         => $cat,
    'pageNo'      => $pageNo,
    'pages'       => $pages,
    'popular'     => fetchAll("SELECT id, title, slug, published_at FROM posts WHERE status='published' ORDER BY views DESC LIMIT 5"),
]);
