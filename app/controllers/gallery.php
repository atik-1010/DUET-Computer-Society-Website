<?php
/** Photo albums and video gallery. */
$section = $params[0] ?? '';
$albumSlug = $params[1] ?? '';

if ($section === '') {
    page('gallery', [
        'title'       => 'Gallery',
        'description' => 'Photographs and video from DUETCS events, contests and workshops.',
        'subtitle'    => 'Photographs and video from every event we have run.',
        'albums'      => fetchAll('SELECT a.*, (SELECT COUNT(*) FROM gallery_photos p WHERE p.album_id = a.id) AS photo_count
                                   FROM gallery_albums a ORDER BY album_date DESC'),
        'videos'      => fetchAll('SELECT * FROM gallery_videos ORDER BY sort_order LIMIT 3'),
    ]);
    return;
}

if ($section === 'videos') {
    page('gallery-videos', [
        'title'       => 'Video Gallery',
        'description' => 'Recorded sessions, aftermovies and full workshop videos.',
        'subtitle'    => 'Recorded sessions, aftermovies and full workshop playbacks.',
        'videos'      => fetchAll('SELECT * FROM gallery_videos ORDER BY sort_order, published_at DESC'),
    ]);
    return;
}

if ($section !== 'photos') {
    notFound();
}

// A single album
if ($albumSlug !== '') {
    $album = fetchOne('SELECT * FROM gallery_albums WHERE slug = ?', [$albumSlug]);
    if (!$album) {
        notFound();
    }
    page('gallery-album', [
        'title'       => $album['title'],
        'description' => $album['description'] ?: SITE_DESC,
        'album'       => $album,
        'photos'      => fetchAll('SELECT * FROM gallery_photos WHERE album_id = ? ORDER BY sort_order', [$album['id']]),
        'others'      => fetchAll('SELECT * FROM gallery_albums WHERE id <> ? ORDER BY album_date DESC LIMIT 4', [$album['id']]),
    ]);
    return;
}

// All albums
page('gallery-photos', [
    'title'       => 'Photo Gallery',
    'description' => 'Albums from DUETCS contests, workshops and ceremonies.',
    'subtitle'    => 'Albums from every contest, workshop and ceremony.',
    'albums'      => fetchAll('SELECT a.*, (SELECT COUNT(*) FROM gallery_photos p WHERE p.album_id = a.id) AS photo_count
                               FROM gallery_albums a ORDER BY album_date DESC'),
]);
