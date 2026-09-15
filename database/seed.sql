-- =====================================================================
--  DUETCS — seed content
--  Names, dates and figures below are realistic placeholders. Replace
--  them from the admin panel with your society's real information.
-- =====================================================================

-- ------------------------------------------------------ membership plans
INSERT INTO membership_plans (name, slug, fee, duration_months, tagline, benefits, is_featured, sort_order) VALUES
('General Member', 'general', 300.00, 12, 'For every DUET student who wants in.',
 'Access to all DUETCS workshops and seminars\nDiscounted registration for contests and bootcamps\nMember-only resource library and lab access\nDigital membership card and participation certificates\nInvitation to the annual tech fest', 0, 1),
('Executive Member', 'executive', 600.00, 12, 'Run the events, not just attend them.',
 'Everything in General Member\nEligibility to contest Executive Committee positions\nOrganiser credit and volunteer certificates\nPriority seats in industry sessions and company tours\nMentorship pairing with an alumnus\nDUETCS T-shirt and ID lanyard', 1, 2),
('Lifetime Member', 'lifetime', 1500.00, 0, 'One payment. Alumni access forever.',
 'Everything in Executive Member\nLifetime access — never renew again\nPermanent alumni directory listing\nSpeaking and judging invitations\nAccess to the alumni referral network\nName engraved on the DUETCS founders'' board', 0, 3);

-- ------------------------------------------- mobile wallet configuration
INSERT INTO payment_methods (code, label, number, account_type, brand_color, instructions, sort_order) VALUES
('bkash', 'bKash', '01712-345678', 'merchant', '#e2136e',
 'Open the bKash app or dial *247#\nChoose "Payment" (for Merchant accounts)\nEnter the DUETCS merchant number shown above\nEnter the exact amount for your selected plan\nUse your Student ID as the reference\nEnter your bKash PIN to confirm\nCopy the Transaction ID (TrxID) from the confirmation SMS', 1),
('nagad', 'Nagad', '01812-345678', 'merchant', '#ec1c24',
 'Open the Nagad app or dial *167#\nChoose "Send Money"\nEnter the DUETCS number shown above\nEnter the exact amount for your selected plan\nWrite your Student ID in the reference field\nEnter your Nagad PIN to confirm\nCopy the Transaction ID (TrxID) from the confirmation SMS', 2),
('rocket', 'Rocket', '018123456781', 'agent', '#8c3494',
 'Open the Rocket app or dial *322#\nChoose "Send Money"\nEnter the DUETCS account number shown above\nEnter the exact amount for your selected plan\nEnter your Rocket PIN to confirm\nCopy the Transaction ID (TxnId) from the confirmation SMS', 3);

-- ------------------------------------------------ editable static pages
INSERT INTO pages (slug, title, subtitle, content) VALUES
('about', 'About DUETCS', 'The official computer society of Dhaka University of Engineering & Technology, Gazipur.',
'<p>The <strong>DUET Computer Society (DUETCS)</strong> is the central technology club of Dhaka University of Engineering &amp; Technology, Gazipur. We exist for one reason: to make sure no DUET student graduates without the practical skills, the network and the confidence to compete anywhere in the world.</p>
<p>DUET is unique among Bangladeshi engineering universities — almost every student here arrives as a diploma engineer, already carrying hands-on experience from a polytechnic. DUETCS was built around that strength. We do not start from theory. We start from building things.</p>
<h3>What we actually do</h3>
<p>Across a typical academic year DUETCS runs a competitive programming camp that meets twice a week, four to six technical bootcamps covering web, mobile, AI and security, the Intra-DUET Programming Contest, a national-scale IUPC, an annual hackathon, and a rolling series of seminars where alumni working at Samsung R&amp;D, bKash, Therap, Brain Station 23 and Optimizely come back and tell juniors exactly how they got there.</p>
<h3>Who can join</h3>
<p>Every enrolled DUET student, from any department, in any series. You do not need to already know how to code. A large share of our most active members joined in their first semester knowing nothing beyond a polytechnic C course, and were on an ICPC regional team within two years.</p>
<h3>How we are organised</h3>
<p>DUETCS is run entirely by students. An Executive Committee of 15–20 members is elected each academic year and works under a panel of faculty advisors from the Department of Computer Science &amp; Engineering. Committee members hold specific portfolios — programming, research, development, cyber security, publications, finance, and public relations — so that responsibility is real and traceable rather than ceremonial.</p>'),

('history', 'Our History', 'From a corridor study group in 2011 to a national programming powerhouse.',
'<p>DUETCS did not begin with a constitution or a budget. It began with seven students and a borrowed projector.</p>
<h3>2011 — The corridor years</h3>
<p>A handful of CSE students started meeting in the third-floor corridor of the Academic Building after evening classes to prepare for the ACM ICPC. There was no room allocation, no faculty patron and no name. They solved problems from printed sheets because the lab computers were booked until eight.</p>
<h3>2013 — Formal recognition</h3>
<p>The group was formally recognised by the university administration as the DUET Computer Society, with the Head of the Department of CSE as its first patron. The society received a dedicated room and, more importantly, permission to hold events under the university banner.</p>
<h3>2015 — The first Intra-DUET Programming Contest</h3>
<p>Sixty-two participants, eleven problems and a single scoreboard projected onto a bedsheet. It has run every year since, and is now the qualification route for our ICPC teams.</p>
<h3>2017 — DUET goes to the ICPC Regionals</h3>
<p>Three DUET teams qualified for the ACM ICPC Dhaka Regional for the first time in the university''s history. It is the moment most alumni point to as the turning point — after it, competitive programming stopped being a niche hobby on campus.</p>
<h3>2019 — The first DUET IUPC</h3>
<p>DUETCS hosted its first Inter-University Programming Contest, bringing 84 teams from 31 universities onto campus. It established DUET as a host institution rather than only a participant.</p>
<h3>2020–2021 — Everything online</h3>
<p>The pandemic could have ended the society. Instead the committee moved the entire calendar to Discord and Google Meet, ran a fully remote Intra-DUET contest, and recorded a workshop series that is still the most-watched content we have ever produced.</p>
<h3>2022 — Beyond competitive programming</h3>
<p>Dedicated wings were created for AI &amp; Machine Learning, Cyber Security and Software Development, each with its own lead on the Executive Committee. The society stopped being a contest club and became a general technology society.</p>
<h3>2024 — DUET Hackathon</h3>
<p>The first 36-hour on-campus hackathon ran with 42 teams and industry judges, and produced three projects that were later deployed for real departmental use.</p>
<h3>Today</h3>
<p>DUETCS has over 900 registered members across every department, an active alumni network in six countries, and a calendar that runs from the first week of the semester to the last.</p>'),

('mission', 'Mission & Vision', 'Why DUETCS exists, and where we intend to take it.',
'<h3>Our Mission</h3>
<p>To equip every DUET student with the practical computing skills, the competitive edge and the professional network needed to build a career in technology — regardless of which department they enrolled in or how much they knew when they arrived.</p>
<h3>Our Vision</h3>
<p>A DUET where the gap between what is taught in a classroom and what the industry actually needs is closed by students, for students — and a society recognised nationally as one of the strongest technology clubs in Bangladesh.</p>
<h3>Our Values</h3>
<p><strong>Open by default.</strong> Every workshop recording, every problem set and every slide deck we produce is published free for the whole campus. Knowledge held privately is knowledge wasted.</p>
<p><strong>Merit, not seniority.</strong> Team selection, contest slots and committee portfolios are decided on demonstrated work. Being in a senior series is not a qualification.</p>
<p><strong>Build, then talk.</strong> We measure a year by what was shipped, hosted and won — not by how many notices were published.</p>
<p><strong>Pay it forward.</strong> Everyone who was taught here is expected to teach here. The competitive programming camp is run by students who were sitting in it two years earlier.</p>
<h3>Strategic Goals — 2026 to 2029</h3>
<p>Place at least one DUET team in the top ten of the ACM ICPC Dhaka Regional. Grow the DUET IUPC to 150 teams and full national broadcast. Run a permanent, year-round research wing publishing at least two student papers a year. Establish a placement pipeline with fifteen partner companies. Reach 100% first-year participation in at least one DUETCS programme.</p>'),

('constitution', 'Constitution & Guidelines', 'The rules the society is governed by, adopted 2013 and last amended 2025.',
'<h3>Article I — Name and Status</h3>
<p>The organisation shall be called the <strong>DUET Computer Society</strong>, abbreviated <strong>DUETCS</strong>. It is a non-political, non-profit student organisation of Dhaka University of Engineering &amp; Technology, Gazipur, operating under the Department of Computer Science &amp; Engineering and the Office of the Director of Students'' Welfare.</p>
<h3>Article II — Objectives</h3>
<p>To promote the study and practice of computing; to represent DUET in national and international competitions; to organise educational programmes open to all students; to build links between students, alumni and industry; and to maintain facilities and archives for the benefit of members.</p>
<h3>Article III — Membership</h3>
<p><strong>3.1</strong> Membership is open to every currently enrolled student of DUET, without restriction on department, series or prior experience.<br>
<strong>3.2</strong> Membership requires a completed application and payment of the applicable annual or lifetime fee.<br>
<strong>3.3</strong> Annual membership expires twelve months after approval and may be renewed.<br>
<strong>3.4</strong> Membership may be revoked by a two-thirds vote of the Executive Committee for conduct that brings the society or the university into disrepute.<br>
<strong>3.5</strong> Fees paid are non-refundable once membership is approved.</p>
<h3>Article IV — The Executive Committee</h3>
<p><strong>4.1</strong> The society is governed by an Executive Committee of not fewer than fifteen and not more than twenty-two members.<br>
<strong>4.2</strong> The Committee shall include a President, up to two Vice Presidents, a General Secretary, a Joint Secretary, a Treasurer, an Organising Secretary, and Secretaries for Programming, Research &amp; Development, Cyber Security, Publications, Public Relations, and Event Management.<br>
<strong>4.3</strong> The Committee serves a term of one academic year.<br>
<strong>4.4</strong> Candidates must be members in good standing and must not be in their final semester at the time of nomination.<br>
<strong>4.5</strong> The Committee shall meet at least once a month, and minutes shall be recorded and archived.</p>
<h3>Article V — Elections</h3>
<p><strong>5.1</strong> Elections are held annually, ordinarily in the first month of the academic year.<br>
<strong>5.2</strong> An Election Commission of three members, none of them candidates, is appointed by the faculty advisors.<br>
<strong>5.3</strong> Every member in good standing has one vote.<br>
<strong>5.4</strong> A tie is resolved by a second ballot; a second tie is resolved by the faculty advisors.</p>
<h3>Article VI — Finance</h3>
<p><strong>6.1</strong> Society funds derive from membership fees, event registrations, university grants and sponsorships.<br>
<strong>6.2</strong> All receipts and disbursements shall be recorded, and mobile wallet transactions shall be reconciled against transaction identifiers.<br>
<strong>6.3</strong> The Treasurer shall present accounts to the Committee monthly and to the general membership annually.<br>
<strong>6.4</strong> Any single expenditure above BDT 10,000 requires the counter-approval of a faculty advisor.</p>
<h3>Article VII — Advisors</h3>
<p>Faculty advisors are appointed from the Department of CSE. They hold no vote but may veto any decision that conflicts with university regulations.</p>
<h3>Article VIII — Code of Conduct</h3>
<p>Members shall not engage in plagiarism, cheating in contests, harassment of any kind, or the use of society resources for personal commercial gain. Violations are referred to the Executive Committee and, where warranted, to the university authorities.</p>
<h3>Article IX — Amendment</h3>
<p>This constitution may be amended by a two-thirds majority of the Executive Committee, ratified by a simple majority at a General Meeting, and approved by the faculty advisors.</p>');

-- ------------------------------------------------------------ settings
INSERT INTO settings (`key`, `value`) VALUES
('site_email',      'duetcs@duet.ac.bd'),
('site_phone',      '+880 1700-000000'),
('site_address',    'Department of CSE, Dhaka University of Engineering & Technology, Gazipur-1707'),
('facebook_url',    'https://facebook.com/duetcs'),
('github_url',      'https://github.com/duetcs'),
('linkedin_url',    'https://linkedin.com/company/duetcs'),
('youtube_url',     'https://youtube.com/@duetcs'),
('stat_members',    '900'),
('stat_events',     '120'),
('stat_contests',   '38'),
('stat_years',      '15'),
('payment_note',    'Payments are verified manually by the Treasurer, usually within 24 hours. Keep the confirmation SMS until your membership is approved.'),
('map_embed',       'https://www.google.com/maps?q=Dhaka+University+of+Engineering+and+Technology&output=embed');

-- ---------------------------------------------------------- committees
INSERT INTO committees (id, title, term, type, is_current, description) VALUES
(1, 'Executive Committee', '2026-27', 'executive', 1, 'The committee elected for the 2026-27 academic year, responsible for all programmes, finance and representation of the society.'),
(2, 'Executive Committee', '2025-26', 'executive', 0, 'Delivered the largest DUET IUPC to date and launched the Cyber Security wing''s CTF programme.'),
(3, 'Executive Committee', '2024-25', 'executive', 0, 'Established the annual DUET Hackathon and the alumni mentorship pairing scheme.'),
(4, 'Faculty Advisory Panel', '2026-27', 'advisory', 1, 'Faculty members from the Department of CSE who guide the society and approve its finances.');

INSERT INTO committee_members (committee_id, name, designation, department, series, email, facebook, linkedin, github, sort_order) VALUES
-- Current executive committee (2026-27)
(1, 'Md. Ashiqur Rahman',   'President',                     'CSE', '2021-22', 'president@duetcs.org',  'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 1),
(1, 'Nusrat Jahan Mim',     'Vice President',                'CSE', '2021-22', 'vp1@duetcs.org',        'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 2),
(1, 'Tanvir Hasan Siam',    'Vice President',                'EEE', '2021-22', 'vp2@duetcs.org',        'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 3),
(1, 'Sadia Afrin Nova',     'General Secretary',             'CSE', '2022-23', 'gs@duetcs.org',         'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 4),
(1, 'Rakibul Islam Rafi',   'Joint Secretary',               'CSE', '2022-23', 'js@duetcs.org',         'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 5),
(1, 'Fahmida Akter Ela',    'Treasurer',                     'IPE', '2022-23', 'treasurer@duetcs.org',  'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 6),
(1, 'Mehedi Hasan Joy',     'Organising Secretary',          'CSE', '2022-23', 'organising@duetcs.org', 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 7),
(1, 'Shahriar Kabir Nabil', 'Secretary, Programming',        'CSE', '2022-23', 'programming@duetcs.org','https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 8),
(1, 'Israt Jahan Meem',     'Secretary, Research & Development','CSE','2022-23','research@duetcs.org',  'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 9),
(1, 'Abdullah Al Noman',    'Secretary, Cyber Security',     'CSE', '2022-23', 'security@duetcs.org',   'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 10),
(1, 'Sumaiya Islam Ruhi',   'Secretary, Publications',       'CE',  '2023-24', 'publications@duetcs.org','https://facebook.com/','https://linkedin.com/', 'https://github.com/', 11),
(1, 'Nazmul Hoque Shanto',  'Secretary, Public Relations',   'ME',  '2023-24', 'pr@duetcs.org',         'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 12),
(1, 'Tasnim Rahman Oishi',  'Secretary, Event Management',   'CSE', '2023-24', 'events@duetcs.org',     'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 13),
(1, 'Ariful Islam Adnan',   'Executive Member',              'CSE', '2023-24', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 14),
(1, 'Jarin Tasnim Prova',   'Executive Member',              'TE',  '2023-24', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 15),
(1, 'Redwan Ahmed Rifat',   'Executive Member',              'EEE', '2023-24', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 16),

-- Previous committee 2025-26
(2, 'Sabbir Hossain Rana',  'President',              'CSE', '2020-21', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 1),
(2, 'Mahmuda Khatun Mim',   'Vice President',         'CSE', '2020-21', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 2),
(2, 'Imran Kabir Shuvo',    'General Secretary',      'CSE', '2021-22', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 3),
(2, 'Rifat Ahmed Chowdhury','Treasurer',              'IPE', '2021-22', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 4),
(2, 'Farhana Yasmin Tuli',  'Secretary, Programming', 'CSE', '2021-22', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 5),
(2, 'Mainul Islam Emon',    'Secretary, Cyber Security','CSE','2021-22', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 6),
(2, 'Sharmin Akter Bithi',  'Secretary, Publications','CE',  '2022-23', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 7),
(2, 'Jubayer Ahmed Rian',   'Organising Secretary',   'ME',  '2022-23', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 8),

-- Previous committee 2024-25
(3, 'Naimur Rahman Fahim',  'President',              'CSE', '2019-20', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 1),
(3, 'Sanjida Islam Puja',   'Vice President',         'CSE', '2019-20', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 2),
(3, 'Tahmid Hasan Turjo',   'General Secretary',      'CSE', '2020-21', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 3),
(3, 'Rubaiya Sultana Mou',  'Treasurer',              'TE',  '2020-21', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 4),
(3, 'Asif Mahmud Sourav',   'Secretary, Programming', 'CSE', '2020-21', NULL, 'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 5),
(3, 'Nafisa Tabassum Riya', 'Secretary, Event Management','EEE','2021-22',NULL,'https://facebook.com/', 'https://linkedin.com/', 'https://github.com/', 6),

-- Faculty advisory panel
(4, 'Dr. Md. Mizanur Rahman',   'Chief Advisor · Professor & Head, Dept. of CSE', 'CSE', NULL, 'head.cse@duet.ac.bd', NULL, 'https://linkedin.com/', NULL, 1),
(4, 'Dr. Fatema Tuz Zohra',     'Advisor · Professor, Dept. of CSE',              'CSE', NULL, NULL, NULL, 'https://linkedin.com/', NULL, 2),
(4, 'Dr. Abdul Kader Chowdhury','Advisor · Associate Professor, Dept. of CSE',    'CSE', NULL, NULL, NULL, 'https://linkedin.com/', NULL, 3),
(4, 'Md. Shafiqul Islam',       'Advisor · Assistant Professor, Dept. of CSE',    'CSE', NULL, NULL, NULL, 'https://linkedin.com/', 'https://github.com/', 4);

-- -------------------------------------------------------------- events
INSERT INTO events (title, slug, category, summary, content, venue, start_datetime, end_datetime, registration_url, seats, fee, status, is_featured) VALUES
('DUET Tech Fest 2026', 'duet-tech-fest-2026', 'Festival',
 'Three days of contests, exhibitions, industry talks and a project showcase — the largest event on the DUETCS calendar.',
 '<p>DUET Tech Fest is the flagship annual event of the DUET Computer Society: three full days that pull together everything the society does across the year into one campus-wide programme.</p><h3>What runs across the three days</h3><p><strong>Day 1</strong> opens with the Project &amp; Research Exhibition in the Academic Building foyer, where over sixty student teams demonstrate work built during the year. The afternoon is given to the Tech Talk track with speakers from bKash, Therap BD, Brain Station 23 and Samsung R&amp;D Bangladesh.</p><p><strong>Day 2</strong> is competition day: the Intra-DUET Programming Contest final, a Capture The Flag security contest, a UI/UX design sprint and a robotics line-follower challenge run in parallel.</p><p><strong>Day 3</strong> closes with the 24-hour hackathon final presentations, the prize-giving ceremony and the announcement of the following year''s Executive Committee nominations.</p><h3>Who can attend</h3><p>All sessions are free and open to every DUET student. Registration is required only for the competitive tracks, which have limited seats.</p>',
 'Central Auditorium & Academic Building, DUET', '2026-11-12 09:00:00', '2026-11-14 20:00:00', 'https://forms.gle/duetcs-techfest', 1200, 0, 'upcoming', 1),

('Competitive Programming Bootcamp — Fall 2026', 'cp-bootcamp-fall-2026', 'Bootcamp',
 'An eight-week structured camp taking absolute beginners from basic C++ to graph algorithms and ICPC-level problem solving.',
 '<p>This is the camp that feeds our ICPC teams. It runs twice a week for eight weeks and assumes nothing beyond the C programming taught in a polytechnic diploma.</p><h3>Syllabus</h3><p><strong>Weeks 1–2:</strong> C++ and the STL — vectors, sets, maps, sorting, and how to read a problem statement without panicking.<br><strong>Weeks 3–4:</strong> Complexity analysis, two pointers, prefix sums, binary search on the answer.<br><strong>Weeks 5–6:</strong> Graph theory — BFS, DFS, shortest paths, disjoint set union, minimum spanning trees.<br><strong>Weeks 7–8:</strong> Dynamic programming, number theory, and two full-length mock contests judged on a live scoreboard.</p><h3>Format</h3><p>Every session is 90 minutes of instruction followed by a 60-minute problem set solved in the lab. Attendance below 70% forfeits the completion certificate. All recordings and editorials are published to the member resource library within 24 hours.</p><h3>Requirements</h3><p>A laptop is helpful but not required — lab machines are reserved for camp participants. Bring a Codeforces handle.</p>',
 'CSE Lab 3, DUET', '2026-09-08 16:00:00', '2026-11-01 18:00:00', 'https://forms.gle/duetcs-cp-bootcamp', 80, 0, 'upcoming', 1),

('Seminar: Breaking Into Big Tech from a Bangladeshi Campus', 'seminar-big-tech-2026', 'Seminar',
 'DUET alumni now at Samsung R&D, Google and Optimizely walk through their actual interview loops, timelines and rejections.',
 '<p>An honest, unglamorous session about what it really takes. Four DUET alumni will walk through the full timeline from their fifth semester to their offer letter — including the rejections, which take up most of the talk.</p><h3>Covered</h3><p>How the referral pipeline actually works and why cold applications rarely land. What a Samsung R&amp;D Bangladesh coding round looks like in practice. Preparing system design without production experience. Building a GitHub profile a recruiter will actually open. Negotiating a first offer in the Bangladeshi market.</p><h3>Format</h3><p>Forty minutes of prepared talk, then an open Q&amp;A that historically runs longer than the talk itself. Bring specific questions.</p>',
 'Seminar Room, Dept. of CSE, DUET', '2026-09-24 15:00:00', '2026-09-24 17:30:00', 'https://forms.gle/duetcs-bigtech', 150, 0, 'upcoming', 0),

('Industry Tour: bKash Engineering, Dhaka', 'industry-tour-bkash-2026', 'Industry Tour',
 'A guided visit to the bKash engineering floor for forty members — architecture walkthrough, on-call room and an open Q&A.',
 '<p>A full-day visit to the bKash engineering office in Dhaka, arranged through the DUETCS alumni network.</p><h3>Itinerary</h3><p>Morning: architecture walkthrough of a system handling millions of daily transactions, presented by a principal engineer. Midday: a session on reliability engineering, including a look at the on-call and incident response process. Afternoon: open Q&amp;A with engineering managers and a discussion of the internship pipeline for DUET students.</p><h3>Selection</h3><p>Forty seats. Priority goes to approved DUETCS members in their sixth semester or above. Transport from campus is arranged by the society; participants cover their own lunch.</p>',
 'bKash Head Office, Dhaka', '2026-10-15 08:00:00', '2026-10-15 18:00:00', 'https://forms.gle/duetcs-bkash-tour', 40, 200, 'upcoming', 0),

('Weekly Competitive Programming Contest — Season 9', 'weekly-cp-contest-season-9', 'Contest Series',
 'A rated two-hour contest every Friday evening on our own judge, running through the whole semester.',
 '<p>Season 9 is live and runs every Friday. Five problems, two hours, and a rating system that carries across the season.</p><h3>How it works</h3><p>Contests open at 8:00 PM every Friday and are open to all DUET students, members or not. Problems are set by the Programming wing and reviewed by two alumni testers. A full editorial is published the following morning.</p><h3>Season standings</h3><p>The top eight of the cumulative season leaderboard are automatically seeded into the Intra-DUET Programming Contest final and are considered first for ICPC team formation.</p>',
 'Online (DUETCS Judge) & CSE Lab 2', '2026-08-07 20:00:00', '2026-12-18 22:00:00', 'https://forms.gle/duetcs-weekly', NULL, 0, 'ongoing', 1),

('Open Source Contribution Drive — Hacktober Edition', 'open-source-drive-2026', 'Programme',
 'A month-long guided push to land members'' first real pull requests on live open source projects.',
 '<p>Most students never make a first contribution because the first one is the hardest. This drive removes that barrier with structure and mentorship.</p><h3>Structure</h3><p>Week 1 covers git, forks, branches and how to read a CONTRIBUTING file. Week 2 pairs each participant with a mentor who helps them pick a genuinely beginner-friendly issue. Weeks 3 and 4 are supported working sessions where mentors review your patch before you open the PR upstream.</p><h3>Recognition</h3><p>Every participant with a merged pull request receives a DUETCS contribution certificate, and the three most substantial contributions are featured on the society blog.</p>',
 'CSE Lab 1 & Online', '2026-08-01 00:00:00', '2026-09-30 23:59:00', NULL, NULL, 0, 'ongoing', 0),

('DUET IUPC 2026', 'duet-iupc-2026', 'Contest',
 'The fifth DUET Inter-University Programming Contest — 118 teams from 44 universities competed on campus.',
 '<p>The fifth edition of the DUET Inter-University Programming Contest was our largest to date: 118 teams from 44 universities, a ten-problem set prepared over four months, and a live scoreboard frozen for the final hour.</p><h3>Result</h3><p>The champion team solved nine of ten problems. Two DUET teams finished inside the top fifteen, our best home performance so far. The full standings and the complete problem set with editorials are published in the results archive.</p><h3>Behind it</h3><p>Sixty DUETCS volunteers ran registration, judging support, catering and the venue over a nineteen-hour day. The problem set was authored by six setters and stress-tested by nine external testers from four universities.</p>',
 'Central Auditorium, DUET', '2026-04-18 09:00:00', '2026-04-18 19:00:00', NULL, 400, 0, 'past', 1),

('DUET Hackathon 2026: Build for Campus', 'duet-hackathon-2026', 'Hackathon',
 'Thirty-six hours, 42 teams, and a brief to solve a real DUET campus problem. Three winners are now in production.',
 '<p>Teams of up to four were given a single brief: build something that solves a real problem on the DUET campus, in thirty-six hours, from an empty repository.</p><h3>Winners</h3><p><strong>Champion — Team Nullptr:</strong> a bus-tracking system for the DUET shuttle service using driver phones as GPS beacons. Now running as a pilot for two routes.<br><strong>1st Runner-up — Team SegFault:</strong> a lab equipment booking system replacing the paper register in the CSE department.<br><strong>2nd Runner-up — Team Overflow:</strong> a lost-and-found platform with image matching, deployed on the department server.</p><h3>Judging</h3><p>Judged by four industry engineers and two faculty members on working functionality, code quality, real-world fit and the live demo. Slide decks without a running demo scored zero on the largest criterion.</p>',
 'CSE Building, DUET', '2026-02-20 09:00:00', '2026-02-21 21:00:00', NULL, 200, 0, 'past', 1),

('Cyber Security Awareness Week 2026', 'cyber-security-week-2026', 'Workshop',
 'Five days of hands-on sessions on web exploitation, digital forensics and personal security hygiene, closing with a campus CTF.',
 '<p>A week built around a simple observation: most students can write code long before they understand how it gets broken.</p><h3>Daily sessions</h3><p><strong>Monday:</strong> the OWASP Top 10, demonstrated live against a deliberately vulnerable application.<br><strong>Tuesday:</strong> SQL injection and cross-site scripting — writing the exploit, then writing the fix.<br><strong>Wednesday:</strong> digital forensics and log analysis on a simulated breach.<br><strong>Thursday:</strong> personal security hygiene — password managers, 2FA, phishing, and what to do when an account is compromised.<br><strong>Friday:</strong> a six-hour jeopardy-style Capture The Flag with 31 competing teams.</p><h3>Outcome</h3><p>Two vulnerabilities discovered by participants during the week were responsibly disclosed to the university IT office and have since been patched.</p>',
 'CSE Lab 4, DUET', '2026-01-12 10:00:00', '2026-01-16 17:00:00', NULL, 120, 0, 'past', 0),

('Machine Learning Winter School 2025', 'ml-winter-school-2025', 'Bootcamp',
 'A ten-day intensive from linear regression to transformer fine-tuning, ending with team projects on Bangla NLP datasets.',
 '<p>Ten consecutive days, six hours a day, aimed at students who had finished a statistics course and nothing more.</p><h3>Progression</h3><p>Days 1–3 covered the mathematical groundwork: linear algebra for ML, gradient descent, and regression implemented from scratch in NumPy before any library was allowed. Days 4–6 moved to classical models and scikit-learn. Days 7–8 covered neural networks and PyTorch. Days 9–10 were team project work on Bangla NLP datasets.</p><h3>Projects</h3><p>Eleven teams presented on the final day. The strongest — a Bangla news headline classifier reaching 91% accuracy on a self-collected dataset — was later expanded into a term paper.</p>',
 'CSE Lab 2, DUET', '2025-12-15 09:00:00', '2025-12-24 15:00:00', NULL, 60, 0, 'past', 0),

('Intra-DUET Programming Contest 2025', 'intra-duet-contest-2025', 'Contest',
 'The annual campus championship — 96 participants, three divisions, and the selection route for our ICPC teams.',
 '<p>The oldest continuously running DUETCS event, held every year since 2015. Ninety-six participants competed across three divisions.</p><h3>Divisions</h3><p><strong>Division A</strong> for experienced contestants, <strong>Division B</strong> for intermediates, and a <strong>Novice</strong> division restricted to first-year students, which exists so that beginners compete against peers rather than against three-year veterans.</p><h3>Why it matters</h3><p>The Division A top six form the pool from which DUET''s ACM ICPC Dhaka Regional teams are selected. It is the single most consequential contest on the internal calendar.</p>',
 'CSE Labs 1–3, DUET', '2025-11-08 10:00:00', '2025-11-08 16:00:00', NULL, 120, 0, 'past', 0),

('Freshers Orientation & Tech Onboarding 2025', 'freshers-orientation-2025', 'Orientation',
 'Introducing the incoming series to DUETCS, the campus tech landscape and the first steps of a computing career.',
 '<p>Held in the second week of the semester for every incoming student, from every department.</p><h3>Programme</h3><p>An introduction to what DUETCS does and how to join. A panel of four seniors describing what they wish they had done in their first year. A practical walkthrough of setting up a development environment, a GitHub account and a Codeforces handle — done live, on the spot, with volunteers circulating to help. Finally, an open floor for questions from students who have never spoken to a senior before.</p><h3>Turnout</h3><p>Four hundred and twelve attendees, of whom 208 submitted a membership application within the following week — the strongest onboarding conversion the society has recorded.</p>',
 'Central Auditorium, DUET', '2025-09-14 10:00:00', '2025-09-14 14:00:00', NULL, 500, 0, 'past', 0);

-- ------------------------------------------------- activity categories
INSERT INTO activity_categories (slug, name, icon, tagline, description, accent, sort_order) VALUES
('programming', 'Programming', 'code', 'Algorithms, data structures and contest preparation.',
 'The oldest and largest wing of the society. We run a year-round competitive programming camp, a weekly rated contest series and the selection process for DUET''s ACM ICPC teams. Everything starts from zero — a majority of our current contestants could not write a for-loop in C++ when they joined.', '#6366f1', 1),
('ai-ml', 'AI & Machine Learning', 'cpu', 'From linear regression to deployed models.',
 'The AI &amp; ML wing runs reading groups, winter schools and applied project teams. Our focus is deliberately practical: we prefer a working Bangla NLP model on a self-collected dataset over a reimplementation of a paper nobody will run again.', '#8b5cf6', 2),
('cybersecurity', 'Cybersecurity', 'shield', 'Break it, understand it, then defend it.',
 'Web exploitation, digital forensics, network security and Capture The Flag competition. Every offensive technique we teach is taught alongside its defence, and all practice happens on lab environments we control.', '#ef4444', 3),
('development', 'Development', 'layers', 'Shipping software that people actually use.',
 'Web, mobile and backend engineering. The development wing builds and maintains real systems for the campus — including the software running parts of the CSE department — which means members work with real users, real bugs and real deadlines.', '#06b6d4', 4),
('workshops', 'Workshops', 'book', 'Short, hands-on, laptop-open sessions.',
 'Focused two- to six-hour sessions on a single practical skill: git, Docker, REST APIs, Figma, LaTeX, deployment. No lectures without an exercise. Open to every DUET student regardless of membership.', '#f59e0b', 5),
('seminars', 'Seminars', 'users', 'Industry, research and career conversations.',
 'Talks by alumni, faculty and visiting engineers on where the industry is going and how to enter it. We ask every speaker for specifics — actual timelines, actual salaries, actual rejections — rather than motivation.', '#10b981', 6);

-- ---------------------------------------------------------- activities
INSERT INTO activities (category, title, slug, summary, content, instructor, duration, level, activity_date, is_featured) VALUES
('programming', 'Data Structures Intensive', 'data-structures-intensive',
 'Arrays, linked lists, stacks, queues, trees and heaps — implemented from scratch before any library is allowed.',
 '<p>A six-week track where every structure is implemented by hand before the STL equivalent is introduced. Students who only learn <code>std::vector</code> never develop an intuition for why it is fast.</p><h3>Covered</h3><p>Dynamic arrays and amortised analysis. Singly and doubly linked lists. Stacks and queues, including the two-stack queue. Binary search trees and the balancing problem. Binary heaps and priority queues. Hash tables and collision strategies.</p><h3>Assessment</h3><p>Weekly implementation assignments reviewed by peers, and a final timed contest using only hand-written structures.</p>',
 'Shahriar Kabir Nabil', '6 weeks · 2 sessions/week', 'beginner', '2026-09-10', 1),
('programming', 'Graph Algorithms Deep Dive', 'graph-algorithms-deep-dive',
 'BFS, DFS, Dijkstra, Bellman-Ford, Floyd-Warshall, MST and network flow — the contest problem-setter''s favourite territory.',
 '<p>Graphs are where most contestants stall, because the difficulty is rarely the algorithm — it is recognising that a problem is a graph problem at all.</p><h3>Covered</h3><p>Traversals and connected components. Shortest paths with Dijkstra, Bellman-Ford and Floyd-Warshall, and when each is the right tool. Minimum spanning trees with Kruskal and Prim. Disjoint set union with path compression. Topological sorting and DAG dynamic programming. An introduction to maximum flow and bipartite matching.</p><h3>Prerequisite</h3><p>Comfort with the Data Structures Intensive material or equivalent.</p>',
 'Md. Ashiqur Rahman', '5 weeks', 'intermediate', '2026-10-05', 1),
('programming', 'Dynamic Programming from Scratch', 'dynamic-programming-from-scratch',
 'The single topic that separates a Division B contestant from a Division A one, taught as state design rather than pattern memorisation.',
 '<p>Most DP tutorials teach a catalogue of named problems. That approach collapses the moment a contest presents something unnamed. This track teaches state design instead.</p><h3>Method</h3><p>Every session begins with a brute-force recursive solution, identifies the repeated subproblems, and derives the memoised and then the iterative form. Knapsack, longest common subsequence and coin change appear — but as outcomes of the method, not as things to memorise.</p><h3>Advanced sessions</h3><p>Bitmask DP, DP on trees, and digit DP for the final two weeks.</p>',
 'Farhana Yasmin Tuli', '4 weeks', 'advanced', '2026-11-02', 0),
('ai-ml', 'Machine Learning Foundations', 'machine-learning-foundations',
 'Regression, classification and model evaluation implemented in NumPy before scikit-learn is introduced.',
 '<p>A first course that refuses to start with <code>model.fit()</code>. Participants implement linear and logistic regression, gradient descent and k-means from scratch in NumPy, then move to scikit-learn once they understand what it is doing.</p><h3>Covered</h3><p>Linear and polynomial regression. Logistic regression and decision boundaries. Overfitting, regularisation and the bias-variance tradeoff. Cross-validation and honest evaluation — precision, recall, F1 and why accuracy lies on imbalanced data. Decision trees and random forests.</p>',
 'Israt Jahan Meem', '8 weeks', 'beginner', '2026-09-15', 1),
('ai-ml', 'Deep Learning with PyTorch', 'deep-learning-with-pytorch',
 'Neural networks, CNNs, RNNs and transfer learning, built and trained on lab GPUs.',
 '<p>Hands-on deep learning using PyTorch, with training runs on the department''s GPU workstations.</p><h3>Covered</h3><p>Tensors, autograd and the training loop written by hand. Feed-forward networks and the vanishing gradient problem. Convolutional networks for image classification. Recurrent networks and sequence modelling. Transfer learning and fine-tuning pretrained models — the technique most likely to be useful in an actual project.</p><h3>Final project</h3><p>Teams of two train and evaluate a model on a dataset they collect themselves.</p>',
 'Israt Jahan Meem', '6 weeks', 'intermediate', '2026-10-20', 0),
('ai-ml', 'Bangla NLP Research Group', 'bangla-nlp-research-group',
 'An ongoing group building and publishing on Bangla language datasets — an area badly underserved by existing research.',
 '<p>A standing research group rather than a course. Bangla is spoken by over 250 million people and remains dramatically under-resourced in NLP, which makes it one of the few areas where undergraduate work can still be genuinely novel.</p><h3>Current work</h3><p>A Bangla news headline classification corpus collected from six national dailies. Sentiment analysis on Bangla social media text, including the code-mixed Banglish problem. Fine-tuning multilingual transformer models for Bangla named entity recognition.</p><h3>Joining</h3><p>Open to members who have completed Machine Learning Foundations. The group meets weekly and expects a real time commitment.</p>',
 'Dr. Fatema Tuz Zohra', 'Ongoing · weekly', 'advanced', '2026-08-20', 0),
('cybersecurity', 'Web Application Security', 'web-application-security',
 'The OWASP Top 10 taught as attack and defence pairs, on lab environments we control.',
 '<p>Every technique here is taught twice: once as an attack against a deliberately vulnerable application, then again as the fix in code.</p><h3>Covered</h3><p>SQL injection — the exploit, then prepared statements. Cross-site scripting — the payload, then output escaping and Content Security Policy. Cross-site request forgery and token defence. Broken authentication and session management. Insecure direct object references. File upload vulnerabilities and safe handling.</p><h3>Rules</h3><p>All practice happens on isolated lab targets provided by the society. Testing against systems you do not own is grounds for immediate removal from the wing and referral to the university authorities.</p>',
 'Abdullah Al Noman', '5 weeks', 'intermediate', '2026-09-28', 1),
('cybersecurity', 'CTF Training Camp', 'ctf-training-camp',
 'Preparation for jeopardy-style Capture The Flag competitions across all six standard categories.',
 '<p>Structured practice for national and international CTF events, run by the members who compete in them.</p><h3>Categories</h3><p>Web exploitation. Cryptography — classical ciphers through RSA implementation flaws. Reverse engineering with Ghidra and IDA. Binary exploitation and buffer overflows. Digital forensics — file carving, memory dumps, network captures. OSINT.</p><h3>Format</h3><p>Weekly practice sets on our own scoreboard, plus team participation in at least four external CTFs per semester.</p>',
 'Mainul Islam Emon', 'Ongoing · weekly', 'advanced', '2026-08-15', 0),
('cybersecurity', 'Digital Forensics Workshop', 'digital-forensics-workshop',
 'Evidence handling, disk and memory analysis, and log correlation on a simulated campus breach.',
 '<p>A two-day workshop structured around a single fabricated incident: a compromised departmental server, investigated from first alert to written report.</p><h3>Covered</h3><p>Chain of custody and why a technically correct finding is worthless if evidence handling was sloppy. Disk imaging and file carving. Memory analysis with Volatility. Log correlation across web server, authentication and firewall logs. Writing an incident report an administrator can act on.</p>',
 'Abdullah Al Noman', '2 days', 'intermediate', '2026-07-11', 0),
('development', 'Full Stack Web Development', 'full-stack-web-development',
 'HTML, CSS, JavaScript, PHP and MySQL through to a deployed, authenticated, database-backed application.',
 '<p>A twelve-week track that ends with each participant deploying a working application with real authentication and a real database.</p><h3>Progression</h3><p>Weeks 1–3: semantic HTML, modern CSS layout with flexbox and grid, and responsive design.<br>Weeks 4–6: JavaScript fundamentals, the DOM, fetch and asynchronous code.<br>Weeks 7–9: PHP, MySQL, prepared statements, sessions, and secure password handling. Security is taught here, not bolted on later.<br>Weeks 10–12: file uploads, an admin panel, and deployment to a live host.</p><h3>Deliverable</h3><p>A deployed project with source on GitHub. No certificate without a working URL.</p>',
 'Rakibul Islam Rafi', '12 weeks', 'beginner', '2026-09-05', 1),
('development', 'Modern JavaScript & React', 'modern-javascript-react',
 'ES6+, component architecture, hooks and state management, built around one substantial application.',
 '<p>For students who already have HTML, CSS and basic JavaScript and want to work the way the industry currently works.</p><h3>Covered</h3><p>ES6+ — destructuring, modules, promises, async/await. React components, props and composition. Hooks: useState, useEffect, useContext, useReducer, and writing your own. Client-side routing. Consuming REST APIs and handling loading and error states properly. Build tooling and production deployment.</p>',
 'Mehedi Hasan Joy', '8 weeks', 'intermediate', '2026-10-12', 0),
('development', 'Mobile App Development with Flutter', 'flutter-mobile-development',
 'One codebase, two platforms — widgets, state, local storage and publishing to the Play Store.',
 '<p>Flutter is the fastest route from zero to a real app on a real phone, which makes it the right first mobile framework for most students.</p><h3>Covered</h3><p>Dart fundamentals. The widget tree and layout. Navigation and routing. State management with Provider and Riverpod. Local persistence with SQLite and shared preferences. Consuming REST APIs. Building a signed release and publishing to the Google Play Store.</p>',
 'Ariful Islam Adnan', '7 weeks', 'intermediate', '2026-11-08', 0),
('development', 'Git, GitHub & Team Workflow', 'git-github-team-workflow',
 'Branching, merging, pull requests and resolving conflicts without losing work or nerve.',
 '<p>The single most immediately useful workshop we run, and the one most students wish they had attended a year earlier.</p><h3>Covered</h3><p>The three trees — working directory, staging area, repository. Branching and merging. Rebase, and when not to use it. Pull requests and code review etiquette. Resolving merge conflicts calmly. Recovering from mistakes with reflog. The .gitignore file and why committing your database password matters.</p>',
 'Sadia Afrin Nova', '1 day', 'beginner', '2026-09-01', 0),
('workshops', 'Docker & Deployment Essentials', 'docker-deployment-essentials',
 'Containers, images, compose files and getting a project onto a live server that stays up.',
 '<p>A one-day workshop bridging the gap between "it works on my laptop" and "it is running at a URL".</p><h3>Covered</h3><p>What a container actually is. Writing a Dockerfile. Docker Compose for multi-service applications. Environment variables and secret handling. Deploying to a VPS, configuring a reverse proxy, and obtaining a TLS certificate.</p>',
 'Tanvir Hasan Siam', '1 day · 6 hours', 'intermediate', '2026-10-25', 0),
('workshops', 'UI/UX Design with Figma', 'ui-ux-design-figma',
 'Design fundamentals, prototyping and handoff for engineers who have to design their own interfaces.',
 '<p>Aimed squarely at developers, not designers. Most student projects are functional and unusable; this workshop addresses the second half.</p><h3>Covered</h3><p>Layout, spacing systems and visual hierarchy. Type scales and readable colour with adequate contrast. Component libraries and design tokens. Interactive prototyping. Exporting assets and specifications a developer can build from.</p>',
 'Tasnim Rahman Oishi', '2 days', 'beginner', '2026-09-19', 0),
('workshops', 'LaTeX for Thesis & Research Papers', 'latex-for-thesis',
 'Producing a thesis that formats itself, with proper citations, figures and equations.',
 '<p>Every DUET student writes a thesis. Most of them fight Microsoft Word for weeks doing it. This workshop is the alternative.</p><h3>Covered</h3><p>Document structure and the DUET thesis template. Mathematical typesetting. Figures, tables and automatic cross-referencing. BibTeX citations and bibliography management. Version control for a thesis. Common errors and how to read a LaTeX error message.</p>',
 'Sumaiya Islam Ruhi', '1 day', 'beginner', '2026-11-15', 0),
('seminars', 'Career Paths in Software Engineering', 'career-paths-software-engineering',
 'Frontend, backend, mobile, data, DevOps and security — what each role does daily and what it pays in Bangladesh.',
 '<p>A grounded overview of what the roles actually involve, presented by people currently doing them.</p><h3>Covered</h3><p>What a day genuinely looks like in each role. The skills each path requires and the realistic time to become employable. Salary ranges in the Bangladeshi market at entry, mid and senior levels. The remote and international contracting route. How to pick a direction without closing doors permanently.</p>',
 'DUETCS Alumni Panel', '2 hours', 'beginner', '2026-09-24', 0),
('seminars', 'Research & Higher Studies Abroad', 'research-higher-studies-abroad',
 'MS and PhD applications, funding, GRE and IELTS, and finding a supervisor who will actually reply.',
 '<p>Presented by DUET graduates currently in funded graduate programmes in the US, Germany, Canada and Japan.</p><h3>Covered</h3><p>Building a research profile as an undergraduate. Writing a statement of purpose that is not generic. Emailing prospective supervisors — what gets read and what gets deleted. Funding: assistantships, scholarships and what fully funded really means. GRE and IELTS timelines. Application costs and how to manage them on a student budget.</p>',
 'DUETCS Alumni Panel', '3 hours', 'beginner', '2026-10-08', 0),
('seminars', 'Startups & Freelancing from Campus', 'startups-freelancing-from-campus',
 'Building an income stream or a product while still enrolled — honestly, including what fails.',
 '<p>A session on earning and building during study, with speakers who have done both successfully and unsuccessfully.</p><h3>Covered</h3><p>Getting a first client on Upwork or Fiverr when you have no portfolio. Pricing, scoping and refusing bad work. Payment and remittance from Bangladesh. Validating a product idea before building it. Balancing this against a CGPA. What the failed attempts cost and what they taught.</p>',
 'Rifat Ahmed Chowdhury', '2 hours', 'beginner', '2026-11-20', 0);

-- -------------------------------------------------------- competitions
INSERT INTO competitions (type, title, slug, summary, content, event_date, venue, teams, participants, prize_pool, champion, register_url, result_url, status) VALUES
('iupc', 'DUET IUPC 2027', 'duet-iupc-2027',
 'The sixth DUET Inter-University Programming Contest. Registration opens in December 2026.',
 '<p>The sixth edition of our national inter-university contest. We are targeting 150 teams, which would make it one of the largest IUPCs held in Bangladesh.</p><h3>Format</h3><p>Teams of three from any recognised university. Five hours, ten to twelve problems, ICPC rules — one machine per team, no external resources, printed materials only. The scoreboard freezes with one hour remaining.</p><h3>Registration</h3><p>Opens December 2026. Registration fee covers meals, the contest kit and certificates for all team members. Early registration is discounted.</p><h3>Preparation</h3><p>A mirror contest and the full previous-year problem set with editorials are published in advance for participating teams.</p>',
 '2027-04-16', 'Central Auditorium, DUET', 150, 450, 'BDT 150,000', NULL, 'https://forms.gle/duetcs-iupc-2027', NULL, 'upcoming'),
('iupc', 'DUET IUPC 2026', 'duet-iupc-2026-contest',
 '118 teams from 44 universities. Our largest and most competitive edition to date.',
 '<p>The fifth DUET IUPC drew 118 teams from 44 universities, with a problem set built over four months by six setters and stress-tested by nine external testers.</p><h3>Final standings — top five</h3><p>1. BUET Rayo — 9 problems<br>2. SUST Mad Scientists — 9 problems<br>3. DU Turbo — 8 problems<br>4. CUET Titans — 8 problems<br>5. RUET Phoenix — 8 problems</p><p><strong>DUET Nullptr</strong> finished 11th and <strong>DUET Recursion</strong> 14th — the best home performance in the contest''s history.</p><h3>Problem set</h3><p>Ten problems spanning ad-hoc, greedy, graph theory, dynamic programming, number theory and computational geometry. Problem J was solved by only two teams. Full editorials are published in the archive.</p>',
 '2026-04-18', 'Central Auditorium, DUET', 118, 354, 'BDT 120,000', 'BUET Rayo', NULL, 'https://duetcs.org/results/iupc-2026', 'past'),
('iupc', 'DUET IUPC 2025', 'duet-iupc-2025',
 '94 teams from 37 universities, and the first year with live scoreboard streaming.',
 '<p>The fourth edition, and the first we streamed live with commentary — an experiment that drew over four thousand concurrent viewers during the final hour.</p><h3>Champion</h3><p>BUET Potatoes solved 8 of 11 problems. DUET SegFault placed 19th.</p><h3>Notes</h3><p>The problem set was criticised, fairly, for a difficulty cliff between problems D and E. The feedback directly shaped the smoother 2026 set.</p>',
 '2025-05-10', 'Central Auditorium, DUET', 94, 282, 'BDT 100,000', 'BUET Potatoes', NULL, 'https://duetcs.org/results/iupc-2025', 'past'),

('intra', 'Intra-DUET Programming Contest 2026', 'intra-duet-contest-2026',
 'The campus championship and the selection route for our ACM ICPC teams. Held during Tech Fest.',
 '<p>The oldest continuously running DUETCS competition, held every year since 2015, and the contest that decides ICPC team selection.</p><h3>Divisions</h3><p><strong>Division A</strong> — open, for experienced contestants.<br><strong>Division B</strong> — for intermediates who have not placed in the top ten of a previous Division A.<br><strong>Novice</strong> — restricted to first-year students, so that beginners compete against peers.</p><h3>Selection</h3><p>The top six of Division A enter the ICPC training pool. Team composition is finalised after a further two selection rounds.</p><h3>Prizes</h3><p>Cash prizes, medals and certificates in every division, plus a mechanical keyboard for the Novice champion.</p>',
 '2026-11-13', 'CSE Labs 1-3, DUET', NULL, 120, 'BDT 40,000', NULL, 'https://forms.gle/duetcs-intra-2026', NULL, 'upcoming'),
('intra', 'Intra-DUET Programming Contest 2025', 'intra-duet-contest-2025-comp',
 '96 participants across three divisions; the Division A champion later led DUET''s top ICPC team.',
 '<p>Ninety-six participants competed across three divisions over six hours.</p><h3>Champions</h3><p><strong>Division A:</strong> Shahriar Kabir Nabil (CSE, 2022-23) — 7 of 9 problems.<br><strong>Division B:</strong> Jarin Tasnim Prova (TE, 2023-24) — 6 of 9.<br><strong>Novice:</strong> Ariful Islam Adnan (CSE, 2023-24) — 5 of 7.</p><h3>Outcome</h3><p>The Division A top six formed the pool for the 2026 ICPC Dhaka Regional teams.</p>',
 '2025-11-08', 'CSE Labs 1-3, DUET', NULL, 96, 'BDT 35,000', 'Shahriar Kabir Nabil', NULL, 'https://duetcs.org/results/intra-2025', 'past'),
('intra', 'DUET Girls Programming Contest 2026', 'duet-girls-programming-contest-2026',
 'A dedicated contest for women in computing at DUET, with a preparatory camp running beforehand.',
 '<p>Women remain badly under-represented in competitive programming at DUET, and a dedicated contest with a preparatory camp has measurably improved participation.</p><h3>Structure</h3><p>A four-week preparatory camp precedes the contest, open to all female DUET students regardless of experience. The contest itself runs three hours with eight problems weighted towards the beginner end.</p><h3>Result of the 2025 edition</h3><p>Participation rose from 14 to 38 contestants year on year, and four participants went on to compete in the open Division B.</p>',
 '2026-09-27', 'CSE Lab 2, DUET', NULL, 45, 'BDT 25,000', NULL, 'https://forms.gle/duetcs-gpc-2026', NULL, 'upcoming'),

('hackathon', 'DUET Hackathon 2027: Build for Bangladesh', 'duet-hackathon-2027',
 '36 hours to build something that addresses a real problem in Bangladesh, judged on a working demo.',
 '<p>The third DUET Hackathon widens the brief from campus problems to national ones.</p><h3>Tracks</h3><p>Education technology. Agriculture and rural logistics. Healthcare access. Financial inclusion. Climate and disaster response.</p><h3>Rules</h3><p>Teams of up to four. Empty repository at the start — no pre-written code. Open source libraries are permitted and expected. Judging weights a working demo far above a presentation; a deck with no running software cannot place.</p><h3>Judging criteria</h3><p>Working functionality (40%), real-world impact and feasibility (25%), technical quality (20%), presentation and demo (15%).</p>',
 '2027-02-19', 'CSE Building, DUET', 50, 200, 'BDT 200,000', NULL, 'https://forms.gle/duetcs-hackathon-2027', NULL, 'upcoming'),
('hackathon', 'DUET Hackathon 2026: Build for Campus', 'duet-hackathon-2026-comp',
 '42 teams, 36 hours, and three winning projects now running in production on campus.',
 '<p>Forty-two teams built solutions to real DUET campus problems in thirty-six continuous hours.</p><h3>Winners</h3><p><strong>Champion — Team Nullptr:</strong> DUET Bus Tracker, using driver phones as GPS beacons. Now piloted on two routes.<br><strong>1st Runner-up — Team SegFault:</strong> a lab equipment booking system, replacing the CSE department''s paper register.<br><strong>2nd Runner-up — Team Overflow:</strong> a campus lost-and-found with image matching, deployed on the department server.</p><h3>What made it work</h3><p>Restricting the brief to campus problems meant every team had direct access to their actual users. Three of the top five projects were tested on real users during the event itself.</p>',
 '2026-02-20', 'CSE Building, DUET', 42, 158, 'BDT 150,000', 'Team Nullptr', NULL, 'https://duetcs.org/results/hackathon-2026', 'past'),

('other', 'DUET Capture The Flag 2026', 'duet-ctf-2026',
 'A six-hour jeopardy-style security contest across web, crypto, forensics, reversing and binary exploitation.',
 '<p>Run by the Cyber Security wing as the closing event of Cyber Security Awareness Week.</p><h3>Format</h3><p>Jeopardy style, teams of up to three, six hours, thirty-two challenges across six categories with dynamic scoring that decreases as more teams solve a challenge.</p><h3>2026 result</h3><p>Thirty-one teams competed. Team <strong>0xDEADBEEF</strong> won with 4,850 points, clearing every category except the final binary exploitation challenge, which nobody solved.</p><h3>Ethics</h3><p>All challenges run on infrastructure the society controls. Attacking anything outside the designated scope is disqualification and referral to the university.</p>',
 '2026-01-16', 'CSE Lab 4, DUET', 31, 89, 'BDT 30,000', 'Team 0xDEADBEEF', NULL, 'https://duetcs.org/results/ctf-2026', 'past'),
('other', 'DUET Project Showcase 2026', 'duet-project-showcase-2026',
 'An exhibition and competition for student-built software and hardware projects, judged by industry engineers.',
 '<p>Held on the first day of Tech Fest, the showcase is where a year of project work becomes visible to the whole campus.</p><h3>Categories</h3><p>Web and mobile applications. Machine learning and data science. IoT and embedded systems. Research prototypes.</p><h3>Judging</h3><p>Six judges drawn from industry and faculty, scoring on originality, technical depth, completeness and the quality of the live demonstration.</p><h3>Eligibility</h3><p>Any DUET student or team. Academic coursework projects are eligible provided the work is the team''s own.</p>',
 '2026-11-12', 'Academic Building Foyer, DUET', 60, 180, 'BDT 60,000', NULL, 'https://forms.gle/duetcs-showcase-2026', NULL, 'upcoming'),
('other', 'ACM ICPC Dhaka Regional 2025 — DUET Contingent', 'icpc-dhaka-regional-2025',
 'Three DUET teams competed at the national regional; our best team finished 27th of 168.',
 '<p>DUET fielded three teams at the ACM ICPC Dhaka Regional, the most competitive programming event in the country.</p><h3>Results</h3><p><strong>DUET Nullptr</strong> — 27th of 168 teams, 5 problems solved. The best placement in DUET''s history.<br><strong>DUET Recursion</strong> — 43rd, 4 problems.<br><strong>DUET SegFault</strong> — 61st, 3 problems.</p><h3>Preparation</h3><p>The contingent trained for five months: three weekly practice contests, individual topic assignments, and four mirror contests against teams from other universities. The training log and problem archive are available to members.</p>',
 '2025-12-06', 'Dhaka', NULL, 9, NULL, NULL, NULL, 'https://icpc.global/', 'past'),
('other', 'National Hackathon 2026 — DUET Representation', 'national-hackathon-2026-duet',
 'Two DUET teams qualified for the national round; one placed in the top ten.',
 '<p>Two teams selected from the DUET Hackathon represented the university at the national round in Dhaka.</p><h3>Result</h3><p>Team Nullptr placed 8th of 96 national finalists with an expanded version of the DUET Bus Tracker, extended to support multi-operator intercity routes.</p><h3>Support</h3><p>DUETCS covered travel and accommodation for both teams from the society fund, approved under Article VI of the constitution.</p>',
 '2026-05-22', 'Dhaka', 2, 8, NULL, NULL, NULL, NULL, 'past');

-- ------------------------------------------------------------ projects
INSERT INTO projects (title, slug, summary, content, category, tech_stack, team_members, github_url, demo_url, year, status, is_featured) VALUES
('DUET Bus Tracker', 'duet-bus-tracker',
 'Live tracking for the DUET shuttle service, using driver phones as GPS beacons. Piloted on two campus routes.',
 '<p>The DUET shuttle has no published timetable that survives contact with Gazipur traffic. Students wait at stops with no idea whether the bus left ten minutes ago or has not started.</p><h3>How it works</h3><p>A lightweight Android app on the driver''s phone publishes a location every fifteen seconds to a Node.js service. Students see live positions and an arrival estimate on a web map, with no app install required. The estimate is derived from historical travel times per segment rather than straight-line distance, which matters enormously on this route.</p><h3>Status</h3><p>Built during DUET Hackathon 2026, where it won. Currently piloted on the Gazipur Chowrasta and Board Bazar routes with the cooperation of the transport office.</p>',
 'mobile', 'Flutter, Node.js, Express, MongoDB, Leaflet', 'Team Nullptr — Ashiqur Rahman, Mehedi Hasan Joy, Tasnim Rahman Oishi, Ariful Islam Adnan',
 'https://github.com/duetcs/duet-bus-tracker', NULL, '2026', 'ongoing', 1),
('CSE Lab Equipment Booking System', 'lab-equipment-booking-system',
 'Replaced the CSE department''s paper register with a web system tracking every item, booking and return.',
 '<p>The department tracked oscilloscopes, development boards and lab machines in a paper register. Items went missing, double-bookings were routine, and nobody could answer who had what.</p><h3>What it does</h3><p>Every item has a record and a QR code. Students book a slot, staff approve, and the return is scanned. Overdue items generate automatic reminders. The department gets a utilisation report showing which equipment is actually used and which has sat untouched for two years.</p><h3>Adoption</h3><p>In use by the CSE department since March 2026. Reported item loss has dropped to near zero across two semesters.</p>',
 'web', 'PHP, MySQL, Bootstrap, jQuery', 'Team SegFault — Rakibul Islam Rafi, Sadia Afrin Nova, Nazmul Hoque Shanto',
 'https://github.com/duetcs/lab-booking', NULL, '2026', 'completed', 1),
('Bangla News Headline Classifier', 'bangla-news-headline-classifier',
 'A Bangla NLP model classifying news headlines into eight categories at 91% accuracy on a self-collected corpus.',
 '<p>Built by the Bangla NLP research group on a corpus of 48,000 headlines collected from six national dailies over eighteen months.</p><h3>Approach</h3><p>A baseline TF-IDF and linear SVM reached 84%. Fine-tuning a multilingual BERT variant on the same corpus reached 91.3% macro-F1. The gap was largest on the politics/economy boundary, which is genuinely ambiguous even for human annotators — inter-annotator agreement on that pair was 87%.</p><h3>Output</h3><p>The corpus and trained weights are released publicly. The work was expanded into a term paper and submitted to a national undergraduate research conference.</p>',
 'ai', 'Python, PyTorch, Transformers, scikit-learn, BeautifulSoup', 'Israt Jahan Meem, Nusrat Jahan Mim, Redwan Ahmed Rifat',
 'https://github.com/duetcs/bangla-headline-classifier', NULL, '2026', 'completed', 1),
('Campus Lost & Found', 'campus-lost-and-found',
 'A lost-and-found platform with image-similarity matching, deployed on the department server.',
 '<p>Lost items on campus were reported through scattered Facebook groups and almost never recovered.</p><h3>How it works</h3><p>Found items are photographed and posted. Lost item reports are matched against the found database using image embeddings, so a student who describes their item badly still gets matches. Claims require a verification detail not visible in the photo.</p><h3>Result</h3><p>Deployed since the 2026 hackathon; 61 items reunited with their owners in the first four months.</p>',
 'web', 'PHP, MySQL, Python, CLIP embeddings, Tailwind CSS', 'Team Overflow — Abdullah Al Noman, Jarin Tasnim Prova, Sumaiya Islam Ruhi',
 'https://github.com/duetcs/lost-and-found', NULL, '2026', 'completed', 0),
('DUETCS Online Judge', 'duetcs-online-judge',
 'The society''s own contest judge — sandboxed execution, live scoreboard and editorial publishing.',
 '<p>Running our weekly contest series on external platforms meant we could not set problems freely or control the schedule. So we built our own judge.</p><h3>Features</h3><p>Sandboxed submission execution in Docker with strict CPU, memory and syscall limits. Support for C, C++, Java and Python. ICPC-style scoring with a freezable live scoreboard. Problem authoring with test data upload and validators. Automatic editorial publishing after the contest closes.</p><h3>Scale</h3><p>Has served 71 contests and over 180,000 submissions since deployment, currently running Season 9 of the weekly series.</p>',
 'web', 'Django, PostgreSQL, Docker, Redis, Celery', 'Shahriar Kabir Nabil, Tanvir Hasan Siam, Mehedi Hasan Joy',
 'https://github.com/duetcs/duetcs-judge', NULL, '2025', 'ongoing', 1),
('SmartAttend — Face Recognition Attendance', 'smartattend-face-recognition',
 'Classroom attendance from a single photograph of the room, replacing roll call.',
 '<p>Roll call in a class of ninety consumes ten minutes of every lecture. SmartAttend replaces it with one photograph.</p><h3>Approach</h3><p>A wide shot of the classroom is processed with face detection followed by embedding comparison against enrolled student photos. Ambiguous matches below a confidence threshold are flagged for manual confirmation rather than guessed — a deliberate choice, since a wrong attendance mark is worse than a manual check.</p><h3>Honest limitations</h3><p>Accuracy degrades badly in poor lighting and with students seated behind others. It was trialled in two courses and is not suitable for unattended use. Documented as a research prototype rather than a product.</p>',
 'ai', 'Python, OpenCV, dlib, Flask, SQLite', 'Naimur Rahman Fahim, Asif Mahmud Sourav, Sanjida Islam Puja',
 'https://github.com/duetcs/smartattend', NULL, '2025', 'completed', 0),
('DUET Result Archive', 'duet-result-archive',
 'A searchable archive of published examination results with CGPA calculation and semester trend charts.',
 '<p>Published results are distributed as scattered PDFs that are difficult to search and impossible to analyse.</p><h3>What it does</h3><p>Parses published result PDFs into a structured database. Students look up their own results, calculate CGPA under the DUET grading rules, and see semester-by-semester trends. Department-level statistics are aggregated and anonymised.</p><h3>Privacy</h3><p>Only results already published publicly by the university are ingested. Individual lookup requires the student''s own roll and registration number together.</p>',
 'web', 'PHP, MySQL, Chart.js, Python (PDF parsing)', 'Imran Kabir Shuvo, Rifat Ahmed Chowdhury, Farhana Yasmin Tuli',
 'https://github.com/duetcs/result-archive', NULL, '2025', 'completed', 0),
('IoT Smart Energy Meter', 'iot-smart-energy-meter',
 'A hardware and software system monitoring per-lab electricity consumption in real time.',
 '<p>A joint project between the DUETCS development wing and EEE department students.</p><h3>Hardware</h3><p>ESP32 microcontrollers with current transformer sensors installed on the distribution board of four CSE labs, publishing readings over MQTT.</p><h3>Software</h3><p>A time-series database and dashboard showing live and historical consumption, with anomaly alerts for equipment left running overnight.</p><h3>Result</h3><p>Identified a lab air conditioning unit running continuously through three semester breaks. Fixing that single issue paid for the hardware nine times over in the first year.</p>',
 'iot', 'ESP32, C++, MQTT, InfluxDB, Grafana, Node-RED', 'Tanvir Hasan Siam, Redwan Ahmed Rifat, Nafisa Tabassum Riya',
 'https://github.com/duetcs/smart-energy-meter', NULL, '2025', 'completed', 0);

-- --------------------------------------------------------- blog / posts
INSERT INTO posts (title, slug, excerpt, content, category, tags, author_name, read_minutes, views, status, published_at) VALUES
('How to Start Competitive Programming at DUET', 'how-to-start-competitive-programming-at-duet',
 'A concrete first-90-days plan for DUET students who have never solved a problem on an online judge — written by people who started exactly there.',
 '<p>Almost every strong contestant at DUET started from the same place: a polytechnic C course, no idea what a data structure was, and the vague sense that competitive programming was for other people. Here is what the first ninety days should actually look like.</p><h3>Days 1–10: pick a language and stop deliberating</h3><p>Use C++. Not because other languages cannot work, but because the STL saves you from reimplementing everything and because every editorial you will read is written in it. The deliberation over language choice is where a surprising number of students lose their first month.</p><h3>Days 11–30: solve easy problems, many of them</h3><p>Create a Codeforces account and solve problems rated 800. Solve forty of them. Not thirty-nine. The purpose is not to learn algorithms — it is to stop being afraid of the submit button and to internalise input parsing, edge cases and off-by-one errors until they are automatic.</p><h3>Days 31–60: learn to read editorials properly</h3><p>Give every problem an honest forty-five minutes. If you have not solved it, read the editorial — then close it and implement the solution yourself from memory. Reading an editorial and nodding is not learning. Reimplementing it without looking is.</p><h3>Days 61–90: start competing live</h3><p>Enter every Codeforces Div. 3 and Div. 4 round you can, and the DUETCS weekly contest. You will do badly at first. This is the point. Contest pressure is a separate skill from problem solving and it only develops under contest pressure.</p><h3>What to ignore</h3><p>Ignore anyone who tells you to learn twelve algorithms before your first contest. Ignore your rating for the first three months. Ignore the fact that a first-year student somewhere is already red — that comparison has never helped anybody.</p><h3>Where DUETCS fits</h3><p>Our Fall bootcamp covers exactly this progression with people who will answer your questions. The weekly contest gives you the live practice. Both are free and neither requires membership.</p>',
 'Programming', 'competitive-programming,beginner,codeforces,cpp', 'Shahriar Kabir Nabil', 7, 2140, 'published', '2026-08-10 10:00:00'),

('Why Your First Web Project Should Not Use a Framework', 'why-your-first-web-project-should-not-use-a-framework',
 'React and Laravel are excellent. They are also the wrong place to start, and skipping the fundamentals costs more later than it saves now.',
 '<p>Every semester a first-year student asks whether they should start with React or Laravel. The answer is neither, and the reason is not nostalgia.</p><h3>Frameworks hide the thing you are trying to learn</h3><p>A framework is a set of answers to problems. If you have never had the problem, the answer is just syntax to memorise. You will know that <code>useEffect</code> runs after render without knowing what a render is, and the first time something behaves unexpectedly you will have no model to debug against.</p><h3>What to build instead</h3><p>Build a small application with plain HTML, CSS, JavaScript, PHP and MySQL. Handle a form submission. Write the SQL yourself. Manage a session. Hash a password. Upload a file. Each of these is a genuine problem with a genuine solution, and every framework you touch afterwards will be recognisable as a convenient wrapper around problems you have personally had.</p><h3>The security argument</h3><p>This matters more than the learning argument. Frameworks protect you from SQL injection and CSRF by default, which is excellent — right up until you work on something that does not, and you do not recognise the vulnerability because you have never seen the attack. We have reviewed student projects that concatenated user input straight into a query, written by people who had used an ORM for two years.</p><h3>When to move on</h3><p>After one complete project, deployed, with authentication and a database. Then pick a framework. You will learn it in a week instead of a month, and you will understand what it is doing for you.</p><h3>The counterargument, honestly</h3><p>If you have a job interview in three weeks that requires React, learn React. This advice is about the fastest route to competence, not the fastest route to a specific line on a CV.</p>',
 'Development', 'web-development,php,javascript,beginner,advice', 'Rakibul Islam Rafi', 6, 1680, 'published', '2026-07-22 14:30:00'),

('SQL Injection: The Vulnerability We Still Find in Every Student Project', 'sql-injection-in-student-projects',
 'We reviewed 34 student projects submitted to the DUET Project Showcase. Twenty-nine were injectable. Here is the fix, and why it keeps happening.',
 '<p>Ahead of the 2026 Project Showcase, the Cyber Security wing reviewed thirty-four submitted web projects with the authors'' permission. Twenty-nine had at least one SQL injection vulnerability. Nineteen had one on the login form.</p><h3>The pattern, every time</h3><p>The vulnerable code looks like this:</p><pre><code>$q = "SELECT * FROM user WHERE username=''$username'' AND password=''$password''";</code></pre><p>Submit <code>'' OR ''1''=''1'' -- </code> as the username and the condition is always true. You are logged in as the first user in the table, who is usually the administrator. This takes about four seconds and requires no tools.</p><h3>The fix, which is not difficult</h3><p>Use prepared statements. The query structure is sent to the database separately from the values, so user input can never change what the query means:</p><pre><code>$stmt = $pdo->prepare(''SELECT * FROM users WHERE username = ?'');
$stmt->execute([$username]);</code></pre><p>That is the entire fix. It is not slower, it is not more code, and it works everywhere.</p><h3>While you are there: passwords</h3><p>Nineteen of the thirty-four projects stored passwords as plaintext. If your database is read — and student project databases are read, because they end up on GitHub — every password is exposed, including the ones your users reused elsewhere.</p><pre><code>$hash = password_hash($password, PASSWORD_DEFAULT);
if (password_verify($input, $hash)) { /* correct */ }</code></pre><p>Two functions. Built into PHP. There is no defensible reason not to use them.</p><h3>Why it keeps happening</h3><p>Because tutorials teach it wrong. A large share of the PHP tutorials that rank well in search results were written before 2012 and use <code>mysql_query</code> with string concatenation. Students copy working code, it works, and nobody tells them it is broken until someone attacks it.</p><h3>Check your own project</h3><p>Try logging in with <code>'' OR ''1''=''1'' -- </code> as the username. If you get in, fix it today. The Web Application Security track covers this and the rest of the OWASP Top 10.</p>',
 'Cybersecurity', 'security,sql-injection,php,owasp', 'Abdullah Al Noman', 8, 3310, 'published', '2026-06-15 11:00:00'),

('What We Learned Running a 118-Team Programming Contest', 'lessons-from-running-duet-iupc-2026',
 'The logistics, the failures and the spreadsheet that saved us. An honest retrospective on DUET IUPC 2026.',
 '<p>DUET IUPC 2026 ran with 118 teams, 60 volunteers and one nineteen-hour day. It went well. It also nearly did not, three separate times.</p><h3>What went wrong</h3><p><strong>The network.</strong> Ninety minutes before start, the lab switch handling half the contest machines failed. We had no spare. A volunteer went to Gazipur and back in forty minutes. We now hold two spares for every critical network component, which costs almost nothing and would have saved a year off our lives.</p><p><strong>Problem J.</strong> A geometry problem passed all our stress tests and was solved by two teams out of 118. In hindsight the intended solution required an insight that no reasonable contestant would find under time pressure. Difficulty estimation by people who already know the solution is unreliable; we now require two external testers to solve every problem cold before it is accepted.</p><p><strong>Food.</strong> We ordered for 354 contestants and forgot the 60 volunteers. They ate last, at nine in the evening. This was a failure of basic respect and we have added volunteers to the headcount permanently.</p><h3>What went right</h3><p><strong>The registration spreadsheet.</strong> One shared sheet, one owner, every team''s status, arrival, machine assignment and dietary requirement in one place. Radical simplicity beat the custom system we had considered building.</p><p><strong>Freezing the scoreboard.</strong> The final hour with a frozen board is what makes a contest watchable. Four thousand people watched the stream during that hour.</p><p><strong>The volunteers.</strong> Sixty students gave a full day for nothing but a T-shirt and a certificate. Everything else on this list is secondary.</p><h3>For anyone hosting their first contest</h3><p>Start problem setting four months out. Get external testers. Buy spare hardware. Feed your volunteers first. Assign every task to exactly one named person. And accept that something will break at ninety minutes before start — the goal is not to prevent it but to have someone free to handle it.</p>',
 'Events', 'iupc,contest,organising,retrospective', 'Sadia Afrin Nova', 9, 1420, 'published', '2026-05-02 09:00:00'),

('Getting Your First Open Source Pull Request Merged', 'first-open-source-pull-request',
 'The first contribution is the hardest and it is mostly a psychological problem, not a technical one.',
 '<p>Every year we run the Open Source Contribution Drive, and every year the same thing happens: the technical work takes an afternoon and the fear takes three weeks.</p><h3>The actual barrier</h3><p>Students do not fail to contribute because they cannot code. They fail because opening a pull request on a stranger''s repository feels like walking into someone''s house. That feeling is normal and it is also wrong — maintainers of beginner-friendly projects genuinely want your patch.</p><h3>Finding a real first issue</h3><p>Search GitHub for <code>label:"good first issue"</code> in a language you know, filtered to repositories updated in the last month. The recency filter matters — an abandoned repository will never merge your work no matter how good it is. Read the CONTRIBUTING file before you write a line.</p><h3>Documentation counts</h3><p>Fixing a broken example in a README is a real contribution. Half of our members'' first merged PRs were documentation, and every one of them made the next contribution easier.</p><h3>The mechanics</h3><p>Fork. Clone. Create a branch named for the issue. Make the smallest change that fixes the thing. Commit with a message that explains why, not what. Push. Open the PR referencing the issue number. Then wait, without anxiously re-reading it.</p><h3>On review comments</h3><p>You will get change requests. This is not rejection; it is the process working. Address them, push again, and say thank you. The maintainer spent unpaid time reading your code.</p><h3>What it is actually worth</h3><p>A merged PR on a real project is worth more on a CV than three tutorial projects, because it is externally verifiable and because it demonstrates you can work inside somebody else''s codebase and conventions — which is what a job is.</p>',
 'Development', 'open-source,git,github,beginner', 'Mehedi Hasan Joy', 6, 980, 'published', '2026-08-01 16:00:00'),

('Building a Bangla NLP Dataset from Nothing', 'building-a-bangla-nlp-dataset',
 'Eighteen months of scraping, cleaning and annotating 48,000 headlines — and what we got wrong at every stage.',
 '<p>Bangla is spoken by more than 250 million people and remains dramatically under-resourced in natural language processing. That is a problem, and for an undergraduate researcher it is also an opportunity: the low-hanging fruit has not been picked.</p><h3>Collection</h3><p>We scraped headlines from six national dailies over eighteen months. The scrapers broke constantly — sites redesign, markup changes, rate limits appear. Budget far more time for maintenance than for writing the scraper.</p><h3>Cleaning, which was most of the work</h3><p>Bangla text carries specific problems. Unicode normalisation is not optional: visually identical strings can have different byte sequences, and a model treats them as different tokens. Punctuation is inconsistent across publishers. Roughly 9% of our raw collection was English or transliterated Banglish and needed a separate decision — we kept it, because real Bangla text online contains it.</p><h3>Annotation and the disagreement problem</h3><p>Three annotators labelled every headline into eight categories. Inter-annotator agreement was 87% overall — but only 71% between politics and economy, which are genuinely entangled in Bangladeshi news. We resolved by majority vote and published the disagreement rate, because a dataset that hides its ambiguity misleads everyone who uses it.</p><h3>Results</h3><p>TF-IDF with a linear SVM reached 84% macro-F1. Fine-tuned multilingual BERT reached 91.3%. The remaining errors cluster almost entirely on the category boundaries where humans also disagreed, which suggests the model is near the ceiling this labelling scheme allows.</p><h3>If you are starting one</h3><p>Publish the data, the annotation guidelines and the disagreement statistics. A dataset without its guidelines is not reproducible, and a Bangla dataset that nobody else can build on has wasted most of its value.</p>',
 'AI & ML', 'nlp,bangla,machine-learning,research,dataset', 'Israt Jahan Meem', 8, 1150, 'published', '2026-04-28 13:00:00'),

('A DUET Student''s Guide to Landing an Internship', 'duet-student-guide-to-internships',
 'What actually works, what does not, and the timeline you should be following from your fifth semester.',
 '<p>Compiled from conversations with thirty DUET graduates who secured internships between 2023 and 2026.</p><h3>The timeline</h3><p><strong>Fifth semester:</strong> build one real project and put it on GitHub with a README that explains what it does and how to run it. Not a tutorial clone.<br><strong>Sixth semester:</strong> contribute to open source, attend industry sessions, and start talking to seniors who are already working. Most internships in Bangladesh come through referral.<br><strong>Seventh semester:</strong> apply. Widely. Expect to hear nothing from most applications.</p><h3>What actually gets you an interview</h3><p>A referral from someone inside the company, above everything else. A GitHub profile with real commits over months rather than one weekend. A specific, honest CV — "built and deployed a lab booking system used by the CSE department" beats "proficient in PHP, MySQL, JavaScript, Python, Java, C++".</p><h3>What does not work</h3><p>Mass-applying with an identical cover letter. Listing every technology you have ever opened. Claiming skills you cannot demonstrate in an interview — Bangladeshi technical interviews are practical and this gets caught immediately.</p><h3>The interview itself</h3><p>Expect a coding round of Codeforces Div. 2 A/B difficulty, questions about your own projects — know your own code, including why you chose what you chose — and basic CS fundamentals: OOP, database normalisation, HTTP, and the difference between a process and a thread.</p><h3>On rejection</h3><p>Every graduate we spoke to was rejected repeatedly before an offer. The median was eleven applications per offer. This is normal, it is not a verdict on you, and the students who succeed are largely the ones who kept applying after the ninth rejection.</p>',
 'Career', 'career,internship,jobs,advice', 'Nusrat Jahan Mim', 7, 2760, 'published', '2026-03-18 10:30:00'),

('Setting Up Your Development Environment on a Budget Laptop', 'dev-environment-on-a-budget-laptop',
 'Practical configuration for the 4 GB machines most of us actually own, without pretending a better one is around the corner.',
 '<p>Most development advice assumes hardware most DUET students do not have. This is written for a 4 GB RAM laptop with a mechanical hard drive, because that is what a large share of us are working on.</p><h3>The single highest-value change</h3><p>If you can afford one upgrade, make it an SSD, not RAM. A 256 GB SATA SSD costs less than a semester''s printing budget and will make a five-year-old machine feel new. Compilation, IDE startup and boot time all collapse.</p><h3>Editor</h3><p>VS Code is usable at 4 GB if you are disciplined about extensions — disable everything you are not actively using; each language server holds real memory. If it still struggles, Sublime Text or Neovim are genuinely fine and considerably lighter.</p><h3>Skip Docker locally when you can</h3><p>Docker Desktop is heavy. For PHP and MySQL work, XAMPP uses a fraction of the resources and is entirely adequate for coursework and society projects. Learn Docker on the lab machines.</p><h3>Use the labs deliberately</h3><p>Machine learning training, large compilations and anything GPU-bound should run on department hardware. Write and debug on your laptop; run the heavy job in the lab. The CSE labs are open to members outside class hours.</p><h3>Free tooling that matters</h3><p>The GitHub Student Developer Pack gives you a domain, private repositories, hosting credit and JetBrains IDEs at no cost. It verifies with a DUET student ID and takes ten minutes. A surprising number of students graduate without ever claiming it.</p>',
 'Resources', 'tools,productivity,setup,beginner', 'Tanvir Hasan Siam', 5, 890, 'published', '2026-02-08 12:00:00');

-- ------------------------------------------------------------- gallery
INSERT INTO gallery_albums (id, title, slug, description, cover, album_date) VALUES
(1, 'DUET IUPC 2026', 'duet-iupc-2026-album', 'Contest day — 118 teams, the frozen scoreboard and the closing ceremony.', 'assets/img/ph/gallery-01.svg', '2026-04-18'),
(2, 'DUET Hackathon 2026', 'duet-hackathon-2026-album', 'Thirty-six hours in the CSE building — build, debug, sleep on the floor, present.', 'assets/img/ph/gallery-04.svg', '2026-02-20'),
(3, 'Cyber Security Week 2026', 'cyber-security-week-2026-album', 'Hands-on sessions, the CTF scoreboard and the closing prize-giving.', 'assets/img/ph/gallery-07.svg', '2026-01-16'),
(4, 'Freshers Orientation 2025', 'freshers-orientation-2025-album', 'Four hundred new students meeting the society for the first time.', 'assets/img/ph/gallery-10.svg', '2025-09-14'),
(5, 'ICPC Dhaka Regional 2025', 'icpc-dhaka-regional-2025-album', 'The DUET contingent — three teams, five months of preparation, one long day.', 'assets/img/ph/gallery-12.svg', '2025-12-06');

INSERT INTO gallery_photos (album_id, image, caption, sort_order) VALUES
(1, 'assets/img/ph/gallery-01.svg', 'Teams settling in before the five-hour countdown begins', 1),
(1, 'assets/img/ph/gallery-02.svg', 'The scoreboard moments before the one-hour freeze', 2),
(1, 'assets/img/ph/gallery-03.svg', 'Volunteers running the registration desk at 7 AM', 3),
(1, 'assets/img/ph/gallery-05.svg', 'The judging room during the final hour', 4),
(1, 'assets/img/ph/gallery-09.svg', 'Closing ceremony and prize distribution', 5),
(2, 'assets/img/ph/gallery-04.svg', 'Hour one — forty-two empty repositories', 1),
(2, 'assets/img/ph/gallery-06.svg', 'Somewhere around 3 AM', 2),
(2, 'assets/img/ph/gallery-08.svg', 'Team Nullptr demonstrating the bus tracker to the judges', 3),
(2, 'assets/img/ph/gallery-11.svg', 'Final presentations to the industry panel', 4),
(3, 'assets/img/ph/gallery-07.svg', 'Live exploitation demonstration against a lab target', 1),
(3, 'assets/img/ph/gallery-02.svg', 'The CTF scoreboard in the closing minutes', 2),
(3, 'assets/img/ph/gallery-05.svg', 'Digital forensics session in Lab 4', 3),
(4, 'assets/img/ph/gallery-10.svg', 'The auditorium filling up before the session', 1),
(4, 'assets/img/ph/gallery-03.svg', 'Setting up first GitHub accounts, live', 2),
(4, 'assets/img/ph/gallery-12.svg', 'The senior panel taking questions', 3),
(5, 'assets/img/ph/gallery-12.svg', 'The DUET contingent before departure', 1),
(5, 'assets/img/ph/gallery-06.svg', 'Team Nullptr in the practice session', 2),
(5, 'assets/img/ph/gallery-09.svg', 'Results — 27th of 168, our best placement yet', 3);

INSERT INTO gallery_videos (title, youtube_id, description, duration, published_at, sort_order) VALUES
('DUET IUPC 2026 — Official Aftermovie', 'dQw4w9WgXcQ', 'Nineteen hours of contest day compressed into four minutes.', '4:12', '2026-04-25', 1),
('Competitive Programming Bootcamp — Session 1: Getting Started', 'dQw4w9WgXcQ', 'The opening session of the Fall bootcamp, covering setup and your first submission.', '1:28:40', '2026-09-09', 2),
('DUET Hackathon 2026 — Winner Presentations', 'dQw4w9WgXcQ', 'All three winning teams presenting to the industry judging panel.', '32:18', '2026-02-22', 3),
('SQL Injection Explained — Cyber Security Week', 'dQw4w9WgXcQ', 'A live demonstration of the attack and the prepared-statement fix.', '46:05', '2026-01-14', 4),
('Alumni Session: Breaking Into the Industry', 'dQw4w9WgXcQ', 'Four DUET graduates on how they actually got their first offers.', '1:12:33', '2025-11-30', 5),
('Machine Learning Winter School — Final Project Showcase', 'dQw4w9WgXcQ', 'Eleven teams presenting their Bangla NLP projects on the closing day.', '54:27', '2025-12-24', 6);

-- ------------------------------------------------- resource categories
INSERT INTO resource_categories (slug, name, icon, description, accent, sort_order) VALUES
('programming', 'Programming', 'code', 'Languages, algorithms, data structures and competitive programming practice.', '#6366f1', 1),
('development', 'Development', 'layers', 'Web, mobile and backend engineering — from first HTML page to production deployment.', '#06b6d4', 2),
('ai-ml', 'AI & Machine Learning', 'cpu', 'Mathematics, classical machine learning, deep learning and applied projects.', '#8b5cf6', 3),
('cybersecurity', 'Cybersecurity', 'shield', 'Web security, cryptography, forensics and Capture The Flag practice.', '#ef4444', 4),
('career', 'Career', 'target', 'Interviews, CVs, higher studies, freelancing and everything after graduation.', '#10b981', 5);

INSERT INTO resources (category, title, description, url, type, level, provider, is_free, sort_order) VALUES
-- Programming
('programming', 'Codeforces', 'The contest platform DUETCS practice is built around. Start at rating 800 and work up.', 'https://codeforces.com', 'practice', 'beginner', 'Codeforces', 1, 1),
('programming', 'CSES Problem Set', 'A curated 300-problem set covering every standard competitive programming topic in order.', 'https://cses.fi/problemset/', 'practice', 'intermediate', 'CSES', 1, 2),
('programming', 'C++ Reference', 'The authoritative reference for the standard library. Learn to read it early.', 'https://en.cppreference.com/', 'article', 'beginner', 'cppreference', 1, 3),
('programming', 'Competitive Programmer''s Handbook', 'Antti Laaksonen''s free book — the single best starting text for contest algorithms.', 'https://cses.fi/book/book.pdf', 'book', 'beginner', 'Antti Laaksonen', 1, 4),
('programming', 'USACO Guide', 'A structured path from bronze to platinum with problems attached to every topic.', 'https://usaco.guide/', 'roadmap', 'beginner', 'USACO', 1, 5),
('programming', 'MIT 6.006 — Introduction to Algorithms', 'The full MIT algorithms course with lectures, notes and assignments.', 'https://ocw.mit.edu/courses/6-006-introduction-to-algorithms-spring-2020/', 'course', 'intermediate', 'MIT OpenCourseWare', 1, 6),
('programming', 'VisuAlgo', 'Animated visualisations of data structures and algorithms — invaluable when a concept will not click.', 'https://visualgo.net/', 'tool', 'beginner', 'NUS', 1, 7),
('programming', 'LeetCode', 'Interview-oriented problem practice, organised by company and topic.', 'https://leetcode.com/', 'practice', 'intermediate', 'LeetCode', 1, 8),
('programming', 'AtCoder Beginner Contests', 'Weekly beginner-friendly contests with excellent, clearly written editorials.', 'https://atcoder.jp/', 'practice', 'beginner', 'AtCoder', 1, 9),
-- Development
('development', 'MDN Web Docs', 'The reference for HTML, CSS and JavaScript. Trust it over a random tutorial, always.', 'https://developer.mozilla.org/', 'article', 'beginner', 'Mozilla', 1, 1),
('development', 'The Odin Project', 'A complete, free, project-based full stack curriculum that actually finishes.', 'https://www.theodinproject.com/', 'roadmap', 'beginner', 'The Odin Project', 1, 2),
('development', 'PHP: The Right Way', 'Modern PHP practice — the antidote to the outdated tutorials that dominate search results.', 'https://phptherightway.com/', 'article', 'beginner', 'Community', 1, 3),
('development', 'JavaScript.info', 'The most thorough free JavaScript tutorial available, from basics to advanced.', 'https://javascript.info/', 'course', 'beginner', 'Ilya Kantor', 1, 4),
('development', 'CSS-Tricks Flexbox & Grid Guides', 'The two layout reference pages every developer keeps open in a tab.', 'https://css-tricks.com/snippets/css/a-guide-to-flexbox/', 'article', 'beginner', 'CSS-Tricks', 1, 5),
('development', 'Pro Git', 'The free official Git book. Read chapters 2 and 3 before your first team project.', 'https://git-scm.com/book/en/v2', 'book', 'beginner', 'Scott Chacon', 1, 6),
('development', 'React Official Documentation', 'The rewritten React docs — genuinely excellent, and the correct place to learn React.', 'https://react.dev/learn', 'course', 'intermediate', 'Meta', 1, 7),
('development', 'Laravel Documentation', 'The best-documented PHP framework. Read it after you have written raw PHP.', 'https://laravel.com/docs', 'article', 'intermediate', 'Laravel', 1, 8),
('development', 'Flutter Documentation & Codelabs', 'Official Flutter learning path from first widget to published app.', 'https://docs.flutter.dev/', 'course', 'intermediate', 'Google', 1, 9),
('development', 'Figma', 'Free for students — design and prototype interfaces before you build them.', 'https://www.figma.com/education/', 'tool', 'beginner', 'Figma', 1, 10),
-- AI & ML
('ai-ml', 'Andrew Ng — Machine Learning Specialization', 'The course that has started more ML careers than any other. Start here.', 'https://www.coursera.org/specializations/machine-learning-introduction', 'course', 'beginner', 'Coursera / DeepLearning.AI', 1, 1),
('ai-ml', '3Blue1Brown — Neural Networks', 'Visual, intuitive explanations of what a neural network is actually doing.', 'https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi', 'video', 'beginner', '3Blue1Brown', 1, 2),
('ai-ml', 'fast.ai — Practical Deep Learning', 'Top-down deep learning: train a working model in lesson one, understand it in lesson four.', 'https://course.fast.ai/', 'course', 'intermediate', 'fast.ai', 1, 3),
('ai-ml', 'PyTorch Tutorials', 'Official tutorials from tensors through to distributed training.', 'https://pytorch.org/tutorials/', 'course', 'intermediate', 'PyTorch', 1, 4),
('ai-ml', 'Kaggle Learn & Competitions', 'Short practical courses plus real datasets and competitions to test yourself against.', 'https://www.kaggle.com/learn', 'practice', 'beginner', 'Kaggle', 1, 5),
('ai-ml', 'Hugging Face Course', 'Transformers, tokenisers and fine-tuning — the practical modern NLP stack.', 'https://huggingface.co/learn/nlp-course', 'course', 'intermediate', 'Hugging Face', 1, 6),
('ai-ml', 'Mathematics for Machine Learning', 'The linear algebra, calculus and probability you actually need, in one free book.', 'https://mml-book.github.io/', 'book', 'intermediate', 'Deisenroth et al.', 1, 7),
('ai-ml', 'Papers With Code', 'Research papers paired with their implementations — the fastest way to reproduce results.', 'https://paperswithcode.com/', 'tool', 'advanced', 'Papers With Code', 1, 8),
-- Cybersecurity
('cybersecurity', 'OWASP Top 10', 'The ten vulnerability classes every web developer is expected to know. Non-negotiable reading.', 'https://owasp.org/www-project-top-ten/', 'article', 'beginner', 'OWASP', 1, 1),
('cybersecurity', 'PortSwigger Web Security Academy', 'Free, hands-on labs for every major web vulnerability. The best security training available at any price.', 'https://portswigger.net/web-security', 'practice', 'beginner', 'PortSwigger', 1, 2),
('cybersecurity', 'TryHackMe', 'Guided security learning paths with browser-based lab machines.', 'https://tryhackme.com/', 'practice', 'beginner', 'TryHackMe', 1, 3),
('cybersecurity', 'Hack The Box', 'Realistic penetration testing machines. Harder than TryHackMe and less hand-holding.', 'https://www.hackthebox.com/', 'practice', 'advanced', 'Hack The Box', 1, 4),
('cybersecurity', 'CTF Field Guide', 'Trail of Bits'' introduction to Capture The Flag across all standard categories.', 'https://trailofbits.github.io/ctf/', 'roadmap', 'intermediate', 'Trail of Bits', 1, 5),
('cybersecurity', 'Cryptopals Crypto Challenges', 'Forty-eight exercises that teach cryptography by making you break it.', 'https://cryptopals.com/', 'practice', 'advanced', 'Cryptopals', 1, 6),
('cybersecurity', 'CTFtime', 'The global calendar of Capture The Flag events and team rankings.', 'https://ctftime.org/', 'tool', 'intermediate', 'CTFtime', 1, 7),
-- Career
('career', 'GitHub Student Developer Pack', 'Free domains, hosting credit, private repositories and JetBrains IDEs. Verify with your DUET ID.', 'https://education.github.com/pack', 'tool', 'beginner', 'GitHub', 1, 1),
('career', 'Tech Interview Handbook', 'A practical, well-organised guide to preparing for software engineering interviews.', 'https://www.techinterviewhandbook.org/', 'roadmap', 'intermediate', 'Yangshun Tay', 1, 2),
('career', 'System Design Primer', 'The most-used free resource for system design interview preparation.', 'https://github.com/donnemartin/system-design-primer', 'book', 'advanced', 'Donne Martin', 1, 3),
('career', 'Overleaf CV Templates', 'Clean LaTeX CV templates — free Overleaf accounts for students.', 'https://www.overleaf.com/latex/templates/tagged/cv', 'tool', 'beginner', 'Overleaf', 1, 4),
('career', 'Google Scholar', 'Find papers and identify potential supervisors when applying for higher studies.', 'https://scholar.google.com/', 'tool', 'intermediate', 'Google', 1, 5),
('career', 'BDJobs & LinkedIn Bangladesh', 'Where entry-level engineering roles in Bangladesh are actually advertised.', 'https://www.linkedin.com/jobs/', 'tool', 'beginner', 'LinkedIn', 1, 6),
('career', 'Upwork Getting Started Guide', 'Building a first freelance profile and getting an initial client without a portfolio.', 'https://www.upwork.com/resources/how-to-get-started-on-upwork', 'article', 'beginner', 'Upwork', 1, 7);

-- -------------------------------------------------------- certificates
INSERT INTO certificates (cert_no, verify_code, holder_name, holder_email, student_id, title, type, position, issued_on) VALUES
('DCS-2026-IUPC-0011', 'A7K2M9', 'Md. Ashiqur Rahman', 'ashiqur@duet.ac.bd', '2101001', 'DUET IUPC 2026', 'participation', NULL, '2026-04-20'),
('DCS-2026-HACK-0001', 'H4C3K1', 'Mehedi Hasan Joy', 'mehedi@duet.ac.bd', '2201014', 'DUET Hackathon 2026: Build for Campus', 'achievement', 'Champion — Team Nullptr', '2026-02-22'),
('DCS-2026-CTF-0004',  'C7F8B2', 'Abdullah Al Noman', 'noman@duet.ac.bd', '2201027', 'DUET Capture The Flag 2026', 'achievement', '1st Runner-up', '2026-01-18'),
('DCS-2025-BOOT-0032', 'B9T4X6', 'Sadia Afrin Nova', 'sadia@duet.ac.bd', '2201009', 'Competitive Programming Bootcamp — Fall 2025', 'completion', NULL, '2025-11-05'),
('DCS-2026-VOL-0058',  'V2L7Q3', 'Tasnim Rahman Oishi', 'tasnim@duet.ac.bd', '2301022', 'DUET IUPC 2026 — Organising Volunteer', 'volunteer', NULL, '2026-04-20'),
('DCS-2025-ML-0007',   'M5L1Z8', 'Israt Jahan Meem', 'israt@duet.ac.bd', '2201018', 'Machine Learning Winter School 2025', 'completion', 'Best Project', '2025-12-24');
