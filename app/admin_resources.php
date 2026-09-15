<?php
/**
 * Declarative definition of every admin-managed content type.
 *
 * The generic CRUD engine in controllers/admin.php reads this and builds the
 * list view, the create/edit form, validation and delete handling from it —
 * so adding a new managed table means adding an entry here, not a new file.
 *
 * Field types: text, textarea, html, number, money, date, datetime, select,
 *              checkbox, image, url, email, slug
 */

function adminResources(): array
{
    $levels = ['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'];

    return [
        'events' => [
            'label' => 'Events', 'singular' => 'Event', 'table' => 'events',
            'icon' => 'calendar', 'group' => 'Content',
            'order' => 'start_datetime DESC',
            'search' => ['title', 'summary', 'venue'],
            'upload' => 'events',
            'list' => [
                'title'          => 'Title',
                'category'       => 'Category',
                'start_datetime' => 'Starts',
                'status'         => 'Status',
            ],
            'fields' => [
                'title'            => ['text', 'Title', 'required'],
                'slug'             => ['slug', 'URL slug', 'from:title'],
                'category'         => ['text', 'Category', '', 'e.g. Workshop, Contest, Seminar'],
                'summary'          => ['textarea', 'Short summary', '', 'One or two sentences, shown on cards'],
                'content'          => ['html', 'Full description'],
                'banner'           => ['image', 'Banner image'],
                'venue'            => ['text', 'Venue'],
                'start_datetime'   => ['datetime', 'Start date & time', 'required'],
                'end_datetime'     => ['datetime', 'End date & time'],
                'registration_url' => ['url', 'Registration link'],
                'seats'            => ['number', 'Seats'],
                'fee'              => ['money', 'Fee (BDT)'],
                'status'           => ['select', 'Status', 'required', '', ['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'past' => 'Past', 'cancelled' => 'Cancelled']],
                'is_featured'      => ['checkbox', 'Feature on the home page'],
            ],
        ],

        'activities' => [
            'label' => 'Activities', 'singular' => 'Activity', 'table' => 'activities',
            'icon' => 'code', 'group' => 'Content',
            'order' => 'activity_date DESC',
            'search' => ['title', 'summary', 'instructor'],
            'upload' => 'events',
            'list' => ['title' => 'Title', 'category' => 'Wing', 'level' => 'Level', 'activity_date' => 'Date'],
            'fields' => [
                'category'      => ['select', 'Wing', 'required', '', 'sql:SELECT slug AS id, name AS label FROM activity_categories ORDER BY sort_order'],
                'title'         => ['text', 'Title', 'required'],
                'slug'          => ['slug', 'URL slug', 'from:title'],
                'summary'       => ['textarea', 'Short summary'],
                'content'       => ['html', 'Full description'],
                'image'         => ['image', 'Cover image'],
                'instructor'    => ['text', 'Instructor / lead'],
                'duration'      => ['text', 'Duration', '', 'e.g. 6 weeks · 2 sessions/week'],
                'level'         => ['select', 'Level', 'required', '', $levels],
                'activity_date' => ['date', 'Start date'],
                'is_featured'   => ['checkbox', 'Feature this activity'],
            ],
        ],

        'competitions' => [
            'label' => 'Competitions', 'singular' => 'Competition', 'table' => 'competitions',
            'icon' => 'trophy', 'group' => 'Content',
            'order' => 'event_date DESC',
            'search' => ['title', 'summary', 'champion'],
            'upload' => 'events',
            'list' => ['title' => 'Title', 'type' => 'Type', 'event_date' => 'Date', 'status' => 'Status'],
            'fields' => [
                'type'         => ['select', 'Type', 'required', '', ['iupc' => 'IUPC', 'intra' => 'Intra DUET', 'hackathon' => 'Hackathon', 'other' => 'Other']],
                'title'        => ['text', 'Title', 'required'],
                'slug'         => ['slug', 'URL slug', 'from:title'],
                'summary'      => ['textarea', 'Short summary'],
                'content'      => ['html', 'Full description'],
                'banner'       => ['image', 'Banner image'],
                'event_date'   => ['date', 'Date'],
                'venue'        => ['text', 'Venue'],
                'teams'        => ['number', 'Teams'],
                'participants' => ['number', 'Participants'],
                'prize_pool'   => ['text', 'Prize pool', '', 'e.g. BDT 150,000'],
                'champion'     => ['text', 'Champion'],
                'register_url' => ['url', 'Registration link'],
                'result_url'   => ['url', 'Results link'],
                'status'       => ['select', 'Status', 'required', '', ['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'past' => 'Past']],
            ],
        ],

        'projects' => [
            'label' => 'Projects', 'singular' => 'Project', 'table' => 'projects',
            'icon' => 'layers', 'group' => 'Content',
            'order' => 'id DESC',
            'search' => ['title', 'summary', 'tech_stack'],
            'upload' => 'projects',
            'list' => ['title' => 'Title', 'category' => 'Category', 'year' => 'Year', 'status' => 'Status'],
            'fields' => [
                'title'        => ['text', 'Title', 'required'],
                'slug'         => ['slug', 'URL slug', 'from:title'],
                'summary'      => ['textarea', 'Short summary'],
                'content'      => ['html', 'Case study'],
                'image'        => ['image', 'Cover image'],
                'category'     => ['select', 'Category', '', '', ['web' => 'Web', 'mobile' => 'Mobile', 'ai' => 'AI / ML', 'iot' => 'IoT', 'security' => 'Security', 'other' => 'Other']],
                'tech_stack'   => ['text', 'Tech stack', '', 'Comma separated'],
                'team_members' => ['text', 'Team members'],
                'github_url'   => ['url', 'GitHub URL'],
                'demo_url'     => ['url', 'Live demo URL'],
                'year'         => ['text', 'Year'],
                'status'       => ['select', 'Status', 'required', '', ['ongoing' => 'Ongoing', 'completed' => 'Completed']],
                'is_featured'  => ['checkbox', 'Feature this project'],
            ],
        ],

        'posts' => [
            'label' => 'Blog Posts', 'singular' => 'Post', 'table' => 'posts',
            'icon' => 'book', 'group' => 'Content',
            'order' => 'published_at DESC',
            'search' => ['title', 'excerpt', 'category'],
            'upload' => 'blog',
            'list' => ['title' => 'Title', 'category' => 'Category', 'published_at' => 'Published', 'status' => 'Status', 'views' => 'Views'],
            'fields' => [
                'title'        => ['text', 'Title', 'required'],
                'slug'         => ['slug', 'URL slug', 'from:title'],
                'excerpt'      => ['textarea', 'Excerpt', '', 'Shown on cards and in search results'],
                'content'      => ['html', 'Article body', 'required'],
                'cover'        => ['image', 'Cover image'],
                'category'     => ['text', 'Category', 'required'],
                'tags'         => ['text', 'Tags', '', 'Comma separated'],
                'author_name'  => ['text', 'Author name'],
                'read_minutes' => ['number', 'Reading time (minutes)'],
                'status'       => ['select', 'Status', 'required', '', ['published' => 'Published', 'draft' => 'Draft']],
                'published_at' => ['datetime', 'Publish date'],
            ],
        ],

        'gallery_albums' => [
            'label' => 'Photo Albums', 'singular' => 'Album', 'table' => 'gallery_albums',
            'icon' => 'image', 'group' => 'Media',
            'order' => 'album_date DESC',
            'search' => ['title', 'description'],
            'upload' => 'gallery',
            'list' => ['title' => 'Title', 'album_date' => 'Date'],
            'fields' => [
                'title'       => ['text', 'Title', 'required'],
                'slug'        => ['slug', 'URL slug', 'from:title'],
                'description' => ['textarea', 'Description'],
                'cover'       => ['image', 'Cover image'],
                'album_date'  => ['date', 'Album date'],
            ],
        ],

        'gallery_photos' => [
            'label' => 'Photos', 'singular' => 'Photo', 'table' => 'gallery_photos',
            'icon' => 'image', 'group' => 'Media',
            'order' => 'album_id, sort_order',
            'search' => ['caption'],
            'upload' => 'gallery',
            'list' => ['caption' => 'Caption', 'album_id' => 'Album', 'sort_order' => 'Order'],
            'fields' => [
                'album_id'   => ['select', 'Album', 'required', '', 'sql:SELECT id, title AS label FROM gallery_albums ORDER BY album_date DESC'],
                'image'      => ['image', 'Photo', 'required'],
                'caption'    => ['text', 'Caption'],
                'sort_order' => ['number', 'Sort order'],
            ],
        ],

        'gallery_videos' => [
            'label' => 'Videos', 'singular' => 'Video', 'table' => 'gallery_videos',
            'icon' => 'play', 'group' => 'Media',
            'order' => 'sort_order, published_at DESC',
            'search' => ['title', 'description'],
            'list' => ['title' => 'Title', 'youtube_id' => 'YouTube ID', 'published_at' => 'Published'],
            'fields' => [
                'title'        => ['text', 'Title', 'required'],
                'youtube_id'   => ['text', 'YouTube video ID', 'required', 'The part after watch?v= — not the whole URL'],
                'description'  => ['textarea', 'Description'],
                'duration'     => ['text', 'Duration', '', 'e.g. 12:45'],
                'published_at' => ['date', 'Published on'],
                'sort_order'   => ['number', 'Sort order'],
            ],
        ],

        'resources' => [
            'label' => 'Resources', 'singular' => 'Resource', 'table' => 'resources',
            'icon' => 'book', 'group' => 'Content',
            'order' => 'category, sort_order',
            'search' => ['title', 'description', 'provider'],
            'list' => ['title' => 'Title', 'category' => 'Category', 'type' => 'Type', 'level' => 'Level'],
            'fields' => [
                'category'    => ['select', 'Category', 'required', '', 'sql:SELECT slug AS id, name AS label FROM resource_categories ORDER BY sort_order'],
                'title'       => ['text', 'Title', 'required'],
                'description' => ['textarea', 'Description'],
                'url'         => ['url', 'Link', 'required'],
                'type'        => ['select', 'Type', 'required', '', ['book' => 'Book', 'course' => 'Course', 'video' => 'Video', 'tool' => 'Tool', 'roadmap' => 'Roadmap', 'article' => 'Article', 'practice' => 'Practice']],
                'level'       => ['select', 'Level', 'required', '', $levels],
                'provider'    => ['text', 'Provider'],
                'is_free'     => ['checkbox', 'Free to use'],
                'sort_order'  => ['number', 'Sort order'],
            ],
        ],

        'certificates' => [
            'label' => 'Certificates', 'singular' => 'Certificate', 'table' => 'certificates',
            'icon' => 'award', 'group' => 'Members',
            'order' => 'issued_on DESC',
            'search' => ['cert_no', 'verify_code', 'holder_name', 'title'],
            'list' => ['cert_no' => 'Certificate no.', 'holder_name' => 'Holder', 'title' => 'For', 'issued_on' => 'Issued'],
            'fields' => [
                'cert_no'      => ['text', 'Certificate number', 'required', 'e.g. DCS-2026-IUPC-0011'],
                'verify_code'  => ['text', 'Verification code', 'required', 'Short public code, e.g. A7K2M9'],
                'holder_name'  => ['text', 'Holder name', 'required'],
                'holder_email' => ['email', 'Holder email', '', 'Certificates appear on the holder\'s dashboard when this matches their account'],
                'student_id'   => ['text', 'Student ID'],
                'title'        => ['text', 'Awarded for', 'required', 'Event, contest or course name'],
                'type'         => ['select', 'Type', 'required', '', ['participation' => 'Participation', 'achievement' => 'Achievement', 'completion' => 'Completion', 'volunteer' => 'Volunteer', 'appreciation' => 'Appreciation']],
                'position'     => ['text', 'Position', '', 'e.g. Champion, 1st Runner-up'],
                'issued_on'    => ['date', 'Issued on', 'required'],
                'is_revoked'   => ['checkbox', 'Revoked'],
            ],
        ],

        'committees' => [
            'label' => 'Committees', 'singular' => 'Committee', 'table' => 'committees',
            'icon' => 'users', 'group' => 'Members',
            'order' => 'is_current DESC, term DESC',
            'search' => ['title', 'term'],
            'list' => ['title' => 'Title', 'term' => 'Term', 'type' => 'Type', 'is_current' => 'Current'],
            'fields' => [
                'title'       => ['text', 'Title', 'required'],
                'term'        => ['text', 'Term', 'required', 'e.g. 2026-27'],
                'type'        => ['select', 'Type', 'required', '', ['executive' => 'Executive', 'advisory' => 'Advisory']],
                'is_current'  => ['checkbox', 'This is the current committee'],
                'description' => ['textarea', 'Description'],
            ],
        ],

        'committee_members' => [
            'label' => 'Committee Members', 'singular' => 'Committee member', 'table' => 'committee_members',
            'icon' => 'users', 'group' => 'Members',
            'order' => 'committee_id, sort_order',
            'search' => ['name', 'designation', 'department'],
            'upload' => 'committee',
            'list' => ['name' => 'Name', 'designation' => 'Designation', 'committee_id' => 'Committee', 'sort_order' => 'Order'],
            'fields' => [
                'committee_id' => ['select', 'Committee', 'required', '', 'sql:SELECT id, CONCAT(title, " ", term) AS label FROM committees ORDER BY is_current DESC, term DESC'],
                'name'         => ['text', 'Full name', 'required'],
                'designation'  => ['text', 'Designation', 'required'],
                'department'   => ['text', 'Department'],
                'series'       => ['text', 'Session / series'],
                'photo'        => ['image', 'Photograph'],
                'email'        => ['email', 'Email'],
                'facebook'     => ['url', 'Facebook URL'],
                'linkedin'     => ['url', 'LinkedIn URL'],
                'github'       => ['url', 'GitHub URL'],
                'sort_order'   => ['number', 'Sort order'],
            ],
        ],

        'membership_plans' => [
            'label' => 'Membership Plans', 'singular' => 'Plan', 'table' => 'membership_plans',
            'icon' => 'wallet', 'group' => 'Membership',
            'order' => 'sort_order',
            'search' => ['name'],
            'list' => ['name' => 'Plan', 'fee' => 'Fee', 'duration_months' => 'Months', 'is_active' => 'Active'],
            'fields' => [
                'name'            => ['text', 'Name', 'required'],
                'slug'            => ['slug', 'URL slug', 'from:name'],
                'fee'             => ['money', 'Fee (BDT)', 'required'],
                'duration_months' => ['number', 'Duration in months', '', '0 means lifetime'],
                'tagline'         => ['text', 'Tagline'],
                'benefits'        => ['textarea', 'Benefits', '', 'One benefit per line'],
                'is_featured'     => ['checkbox', 'Highlight as most popular'],
                'is_active'       => ['checkbox', 'Available for new applications'],
                'sort_order'      => ['number', 'Sort order'],
            ],
        ],

        'payment_methods' => [
            'label' => 'Payment Methods', 'singular' => 'Payment method', 'table' => 'payment_methods',
            'icon' => 'wallet', 'group' => 'Membership',
            'order' => 'sort_order',
            'search' => ['label', 'number'],
            'list' => ['label' => 'Wallet', 'number' => 'Number', 'account_type' => 'Account', 'is_active' => 'Active'],
            'fields' => [
                'code'         => ['select', 'Wallet', 'required', '', ['bkash' => 'bKash', 'nagad' => 'Nagad', 'rocket' => 'Rocket']],
                'label'        => ['text', 'Display name', 'required'],
                'number'       => ['text', 'Receiving number', 'required', 'The number students send money to'],
                'account_type' => ['select', 'Account type', 'required', '', ['personal' => 'Personal', 'agent' => 'Agent', 'merchant' => 'Merchant']],
                'brand_color'  => ['text', 'Brand colour', '', 'Hex value, e.g. #e2136e'],
                'instructions' => ['textarea', 'Instructions', '', 'One step per line'],
                'is_active'    => ['checkbox', 'Accept payments through this wallet'],
                'sort_order'   => ['number', 'Sort order'],
            ],
        ],

        'pages' => [
            'label' => 'Static Pages', 'singular' => 'Page', 'table' => 'pages',
            'icon' => 'file', 'group' => 'Settings',
            'order' => 'id',
            'search' => ['title', 'slug'],
            'list' => ['title' => 'Title', 'slug' => 'Slug', 'updated_at' => 'Updated'],
            'fields' => [
                'slug'     => ['text', 'Slug', 'required', 'about · history · mission · constitution'],
                'title'    => ['text', 'Title', 'required'],
                'subtitle' => ['textarea', 'Subtitle'],
                'content'  => ['html', 'Content', 'required'],
            ],
        ],

        'activity_categories' => [
            'label' => 'Activity Wings', 'singular' => 'Wing', 'table' => 'activity_categories',
            'icon' => 'layers', 'group' => 'Settings',
            'order' => 'sort_order',
            'search' => ['name'],
            'list' => ['name' => 'Name', 'slug' => 'Slug', 'sort_order' => 'Order'],
            'fields' => [
                'name'        => ['text', 'Name', 'required'],
                'slug'        => ['slug', 'URL slug', 'from:name'],
                'icon'        => ['text', 'Icon key', '', 'code · cpu · shield · layers · book · users'],
                'tagline'     => ['text', 'Tagline'],
                'description' => ['textarea', 'Description'],
                'accent'      => ['text', 'Accent colour', '', 'Hex value'],
                'sort_order'  => ['number', 'Sort order'],
            ],
        ],

        'resource_categories' => [
            'label' => 'Resource Categories', 'singular' => 'Resource category', 'table' => 'resource_categories',
            'icon' => 'book', 'group' => 'Settings',
            'order' => 'sort_order',
            'search' => ['name'],
            'list' => ['name' => 'Name', 'slug' => 'Slug', 'sort_order' => 'Order'],
            'fields' => [
                'name'        => ['text', 'Name', 'required'],
                'slug'        => ['slug', 'URL slug', 'from:name'],
                'icon'        => ['text', 'Icon key'],
                'description' => ['textarea', 'Description'],
                'accent'      => ['text', 'Accent colour'],
                'sort_order'  => ['number', 'Sort order'],
            ],
        ],
    ];
}
