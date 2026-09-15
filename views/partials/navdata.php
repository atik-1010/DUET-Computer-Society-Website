<?php
/**
 * Single source of truth for the primary navigation.
 * Both the desktop menu and the mobile drawer render from this array.
 */
return [
    ['label' => 'Home', 'url' => ''],

    ['label' => 'About', 'url' => 'about', 'children' => [
        ['label' => 'About DUETCS',           'url' => 'about',              'icon' => 'users'],
        ['label' => 'History',                'url' => 'about/history',      'icon' => 'clock'],
        ['label' => 'Mission & Vision',       'url' => 'about/mission',      'icon' => 'target'],
        ['label' => 'Constitution & Guidelines', 'url' => 'about/constitution', 'icon' => 'file'],
    ]],

    ['label' => 'Committee', 'url' => 'committee', 'children' => [
        ['label' => 'Current Committee',   'url' => 'committee',           'icon' => 'users'],
        ['label' => 'Previous Committees', 'url' => 'committee/previous',  'icon' => 'clock'],
        ['label' => 'Advisors',            'url' => 'committee/advisors',  'icon' => 'award'],
    ]],

    ['label' => 'Events', 'url' => 'events', 'children' => [
        ['label' => 'All Events',      'url' => 'events',          'icon' => 'calendar'],
        ['label' => 'Upcoming Events', 'url' => 'events/upcoming',  'icon' => 'sparkle'],
        ['label' => 'Ongoing Events',  'url' => 'events/ongoing',   'icon' => 'clock'],
        ['label' => 'Past Events',     'url' => 'events/past',      'icon' => 'check'],
    ]],

    ['label' => 'Activities', 'url' => 'activities', 'wide' => true, 'children' => [
        ['label' => 'Programming',           'url' => 'activities/programming',   'icon' => 'code'],
        ['label' => 'AI & Machine Learning', 'url' => 'activities/ai-ml',          'icon' => 'cpu'],
        ['label' => 'Cybersecurity',         'url' => 'activities/cybersecurity',  'icon' => 'shield'],
        ['label' => 'Development',           'url' => 'activities/development',    'icon' => 'layers'],
        ['label' => 'Workshops',             'url' => 'activities/workshops',      'icon' => 'book'],
        ['label' => 'Seminars',              'url' => 'activities/seminars',       'icon' => 'users'],
    ]],

    ['label' => 'Competitions', 'url' => 'competitions', 'children' => [
        ['label' => 'All Competitions',     'url' => 'competitions',            'icon' => 'trophy'],
        ['label' => 'IUPC',                 'url' => 'competitions/iupc',       'icon' => 'award'],
        ['label' => 'Intra DUET Contest',   'url' => 'competitions/intra',      'icon' => 'code'],
        ['label' => 'Hackathon',            'url' => 'competitions/hackathon',  'icon' => 'sparkle'],
        ['label' => 'Other Contests',       'url' => 'competitions/other',      'icon' => 'target'],
    ]],

    ['label' => 'Explore', 'url' => 'projects', 'wide' => true, 'children' => [
        ['label' => 'Projects',       'url' => 'projects',        'icon' => 'layers'],
        ['label' => 'Blog / Articles','url' => 'blog',            'icon' => 'book'],
        ['label' => 'Photo Gallery',  'url' => 'gallery/photos',  'icon' => 'image'],
        ['label' => 'Video Gallery',  'url' => 'gallery/videos',  'icon' => 'play'],
        ['label' => 'Resources',      'url' => 'resources',       'icon' => 'book'],
        ['label' => 'Certificates',   'url' => 'certificates',    'icon' => 'award'],
    ]],

    ['label' => 'Contact', 'url' => 'contact'],
];
