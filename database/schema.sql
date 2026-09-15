-- =====================================================================
--  DUET Computer Society (DUETCS) — database schema
--  MySQL / MariaDB 10.4+   ·   InnoDB   ·   utf8mb4
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS payments, members, membership_plans, payment_methods,
    committee_members, committees, events, activities, activity_categories,
    competitions, projects, posts, gallery_photos, gallery_albums,
    gallery_videos, resources, resource_categories, certificates, pages,
    contacts, subscribers, settings, users;

-- ---------------------------------------------------------------- users
CREATE TABLE users (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name        VARCHAR(100) NOT NULL,
  username    VARCHAR(50)  NOT NULL,
  email       VARCHAR(150) NOT NULL,
  password    VARCHAR(255) NOT NULL,               -- bcrypt hash, never plaintext
  role        ENUM('admin','moderator','member','user') NOT NULL DEFAULT 'user',
  status      ENUM('active','pending','blocked')        NOT NULL DEFAULT 'active',
  avatar      VARCHAR(255) DEFAULT NULL,
  phone       VARCHAR(30)  DEFAULT NULL,
  bio         VARCHAR(255) DEFAULT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login  DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------ membership plans
CREATE TABLE membership_plans (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name            VARCHAR(80)  NOT NULL,
  slug            VARCHAR(80)  NOT NULL,
  fee             DECIMAL(10,2) NOT NULL DEFAULT 0,
  duration_months INT NOT NULL DEFAULT 12,          -- 0 = lifetime
  tagline         VARCHAR(160) DEFAULT NULL,
  benefits        TEXT,                             -- one benefit per line
  is_featured     TINYINT(1) NOT NULL DEFAULT 0,
  is_active       TINYINT(1) NOT NULL DEFAULT 1,
  sort_order      INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_plan_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------- members
CREATE TABLE members (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id     INT UNSIGNED DEFAULT NULL,
  plan_id     INT UNSIGNED DEFAULT NULL,
  member_no   VARCHAR(30)  DEFAULT NULL,
  full_name   VARCHAR(120) NOT NULL,
  email       VARCHAR(150) NOT NULL,
  phone       VARCHAR(30)  NOT NULL,
  student_id  VARCHAR(30)  NOT NULL,
  department  VARCHAR(80)  NOT NULL,
  series      VARCHAR(20)  NOT NULL,
  semester    VARCHAR(30)  DEFAULT NULL,
  blood_group VARCHAR(5)   DEFAULT NULL,
  address     VARCHAR(255) DEFAULT NULL,
  interests   VARCHAR(255) DEFAULT NULL,
  motivation  TEXT,
  photo       VARCHAR(255) DEFAULT NULL,
  status      ENUM('pending','awaiting_payment','approved','rejected','expired')
              NOT NULL DEFAULT 'awaiting_payment',
  admin_note  VARCHAR(255) DEFAULT NULL,
  applied_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  approved_at DATETIME DEFAULT NULL,
  expires_on  DATE DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_member_no (member_no),
  KEY idx_member_status (status),
  KEY idx_member_user (user_id),
  CONSTRAINT fk_member_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_member_plan FOREIGN KEY (plan_id) REFERENCES membership_plans(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------- mobile wallet configuration
CREATE TABLE payment_methods (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  code         VARCHAR(20)  NOT NULL,              -- bkash | nagad | rocket
  label        VARCHAR(40)  NOT NULL,
  number       VARCHAR(30)  NOT NULL,              -- society's receiving number
  account_type ENUM('personal','agent','merchant') NOT NULL DEFAULT 'personal',
  brand_color  VARCHAR(20)  NOT NULL DEFAULT '#e2136e',
  instructions TEXT,                               -- one step per line
  is_active    TINYINT(1) NOT NULL DEFAULT 1,
  sort_order   INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pm_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------ payments
CREATE TABLE payments (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  member_id       INT UNSIGNED NOT NULL,
  plan_id         INT UNSIGNED DEFAULT NULL,
  reference       VARCHAR(40) NOT NULL,            -- our invoice code, e.g. DCS-INV-000123
  method          ENUM('bkash','nagad','rocket') NOT NULL,
  sender_number   VARCHAR(30) NOT NULL,
  receiver_number VARCHAR(30) NOT NULL,
  trx_id          VARCHAR(50) NOT NULL,            -- from the wallet SMS
  amount          DECIMAL(10,2) NOT NULL,
  screenshot      VARCHAR(255) DEFAULT NULL,
  status          ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  admin_note      VARCHAR(255) DEFAULT NULL,
  submitted_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  verified_at     DATETIME DEFAULT NULL,
  verified_by     INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_reference (reference),
  -- the same wallet transaction can never be submitted twice
  UNIQUE KEY uq_method_trx (method, trx_id),
  KEY idx_pay_status (status),
  CONSTRAINT fk_pay_member FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
  CONSTRAINT fk_pay_admin  FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------- committees
CREATE TABLE committees (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title       VARCHAR(120) NOT NULL,
  term        VARCHAR(20)  NOT NULL,               -- e.g. 2025-26
  type        ENUM('executive','advisory') NOT NULL DEFAULT 'executive',
  is_current  TINYINT(1) NOT NULL DEFAULT 0,
  description TEXT,
  PRIMARY KEY (id),
  KEY idx_com_current (is_current)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE committee_members (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  committee_id INT UNSIGNED NOT NULL,
  name         VARCHAR(120) NOT NULL,
  designation  VARCHAR(80)  NOT NULL,
  department   VARCHAR(80)  DEFAULT NULL,
  series       VARCHAR(20)  DEFAULT NULL,
  photo        VARCHAR(255) DEFAULT NULL,
  email        VARCHAR(150) DEFAULT NULL,
  facebook     VARCHAR(255) DEFAULT NULL,
  linkedin     VARCHAR(255) DEFAULT NULL,
  github       VARCHAR(255) DEFAULT NULL,
  sort_order   INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_cm_committee (committee_id),
  CONSTRAINT fk_cm_committee FOREIGN KEY (committee_id) REFERENCES committees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------- events
CREATE TABLE events (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title            VARCHAR(160) NOT NULL,
  slug             VARCHAR(180) NOT NULL,
  category         VARCHAR(60)  NOT NULL DEFAULT 'general',
  summary          VARCHAR(300) DEFAULT NULL,
  content          LONGTEXT,
  banner           VARCHAR(255) DEFAULT NULL,
  venue            VARCHAR(160) DEFAULT NULL,
  start_datetime   DATETIME NOT NULL,
  end_datetime     DATETIME DEFAULT NULL,
  registration_url VARCHAR(255) DEFAULT NULL,
  seats            INT DEFAULT NULL,
  fee              DECIMAL(10,2) NOT NULL DEFAULT 0,
  status           ENUM('upcoming','ongoing','past','cancelled') NOT NULL DEFAULT 'upcoming',
  is_featured      TINYINT(1) NOT NULL DEFAULT 0,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_event_slug (slug),
  KEY idx_event_status (status),
  KEY idx_event_start (start_datetime)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------- activities
CREATE TABLE activity_categories (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug        VARCHAR(60)  NOT NULL,
  name        VARCHAR(80)  NOT NULL,
  icon        VARCHAR(30)  NOT NULL DEFAULT 'code',
  tagline     VARCHAR(160) DEFAULT NULL,
  description TEXT,
  accent      VARCHAR(20)  NOT NULL DEFAULT '#6366f1',
  sort_order  INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_actcat_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE activities (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category      VARCHAR(60)  NOT NULL,             -- -> activity_categories.slug
  title         VARCHAR(160) NOT NULL,
  slug          VARCHAR(180) NOT NULL,
  summary       VARCHAR(300) DEFAULT NULL,
  content       LONGTEXT,
  image         VARCHAR(255) DEFAULT NULL,
  instructor    VARCHAR(120) DEFAULT NULL,
  duration      VARCHAR(60)  DEFAULT NULL,
  level         ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  activity_date DATE DEFAULT NULL,
  is_featured   TINYINT(1) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_activity_slug (slug),
  KEY idx_activity_cat (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------- competitions
CREATE TABLE competitions (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type         VARCHAR(40)  NOT NULL,              -- iupc | intra | hackathon | other
  title        VARCHAR(160) NOT NULL,
  slug         VARCHAR(180) NOT NULL,
  summary      VARCHAR(300) DEFAULT NULL,
  content      LONGTEXT,
  banner       VARCHAR(255) DEFAULT NULL,
  event_date   DATE DEFAULT NULL,
  venue        VARCHAR(160) DEFAULT NULL,
  teams        INT DEFAULT NULL,
  participants INT DEFAULT NULL,
  prize_pool   VARCHAR(60)  DEFAULT NULL,
  result_url   VARCHAR(255) DEFAULT NULL,
  register_url VARCHAR(255) DEFAULT NULL,
  champion     VARCHAR(160) DEFAULT NULL,
  status       ENUM('upcoming','ongoing','past') NOT NULL DEFAULT 'past',
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_comp_slug (slug),
  KEY idx_comp_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------ projects
CREATE TABLE projects (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title        VARCHAR(160) NOT NULL,
  slug         VARCHAR(180) NOT NULL,
  summary      VARCHAR(300) DEFAULT NULL,
  content      LONGTEXT,
  image        VARCHAR(255) DEFAULT NULL,
  category     VARCHAR(60)  NOT NULL DEFAULT 'web',
  tech_stack   VARCHAR(255) DEFAULT NULL,          -- comma separated
  team_members VARCHAR(255) DEFAULT NULL,
  github_url   VARCHAR(255) DEFAULT NULL,
  demo_url     VARCHAR(255) DEFAULT NULL,
  year         VARCHAR(10)  DEFAULT NULL,
  status       ENUM('ongoing','completed') NOT NULL DEFAULT 'completed',
  is_featured  TINYINT(1) NOT NULL DEFAULT 0,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_project_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------- blog / posts
CREATE TABLE posts (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title        VARCHAR(180) NOT NULL,
  slug         VARCHAR(200) NOT NULL,
  excerpt      VARCHAR(320) DEFAULT NULL,
  content      LONGTEXT,
  cover        VARCHAR(255) DEFAULT NULL,
  category     VARCHAR(60)  NOT NULL DEFAULT 'General',
  tags         VARCHAR(255) DEFAULT NULL,
  author_id    INT UNSIGNED DEFAULT NULL,
  author_name  VARCHAR(120) DEFAULT NULL,
  read_minutes INT NOT NULL DEFAULT 4,
  views        INT UNSIGNED NOT NULL DEFAULT 0,
  status       ENUM('draft','published') NOT NULL DEFAULT 'published',
  published_at DATETIME DEFAULT NULL,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_post_slug (slug),
  KEY idx_post_status (status),
  CONSTRAINT fk_post_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------- gallery
CREATE TABLE gallery_albums (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title       VARCHAR(160) NOT NULL,
  slug        VARCHAR(180) NOT NULL,
  description VARCHAR(300) DEFAULT NULL,
  cover       VARCHAR(255) DEFAULT NULL,
  album_date  DATE DEFAULT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_album_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gallery_photos (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  album_id   INT UNSIGNED NOT NULL,
  image      VARCHAR(255) NOT NULL,
  caption    VARCHAR(200) DEFAULT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_photo_album (album_id),
  CONSTRAINT fk_photo_album FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gallery_videos (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title        VARCHAR(160) NOT NULL,
  youtube_id   VARCHAR(30)  NOT NULL,
  description  VARCHAR(300) DEFAULT NULL,
  duration     VARCHAR(20)  DEFAULT NULL,
  published_at DATE DEFAULT NULL,
  sort_order   INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------- resources
CREATE TABLE resource_categories (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug        VARCHAR(60)  NOT NULL,
  name        VARCHAR(80)  NOT NULL,
  icon        VARCHAR(30)  NOT NULL DEFAULT 'book',
  description VARCHAR(300) DEFAULT NULL,
  accent      VARCHAR(20)  NOT NULL DEFAULT '#6366f1',
  sort_order  INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_rescat_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE resources (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category    VARCHAR(60)  NOT NULL,
  title       VARCHAR(180) NOT NULL,
  description VARCHAR(300) DEFAULT NULL,
  url         VARCHAR(300) NOT NULL,
  type        ENUM('book','course','video','tool','roadmap','article','practice') NOT NULL DEFAULT 'course',
  level       ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  provider    VARCHAR(80)  DEFAULT NULL,
  is_free     TINYINT(1) NOT NULL DEFAULT 1,
  sort_order  INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_res_cat (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------- certificates
CREATE TABLE certificates (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  cert_no      VARCHAR(40)  NOT NULL,              -- printed on the certificate
  verify_code  VARCHAR(20)  NOT NULL,              -- short public lookup code
  holder_name  VARCHAR(120) NOT NULL,
  holder_email VARCHAR(150) DEFAULT NULL,
  student_id   VARCHAR(30)  DEFAULT NULL,
  title        VARCHAR(180) NOT NULL,              -- event / course name
  type         ENUM('participation','achievement','completion','volunteer','appreciation')
               NOT NULL DEFAULT 'participation',
  position     VARCHAR(60)  DEFAULT NULL,          -- e.g. "Champion", "1st Runner-up"
  issued_on    DATE NOT NULL,
  file         VARCHAR(255) DEFAULT NULL,
  is_revoked   TINYINT(1) NOT NULL DEFAULT 0,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cert_no (cert_no),
  UNIQUE KEY uq_verify_code (verify_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------ editable static pages
CREATE TABLE pages (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug       VARCHAR(80)  NOT NULL,
  title      VARCHAR(160) NOT NULL,
  subtitle   VARCHAR(300) DEFAULT NULL,
  content    LONGTEXT,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_page_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------- contact
CREATE TABLE contacts (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  phone      VARCHAR(30)  DEFAULT NULL,
  subject    VARCHAR(180) NOT NULL,
  message    TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_contact_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscribers (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email      VARCHAR(150) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_sub_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------ settings
CREATE TABLE settings (
  `key`   VARCHAR(60) NOT NULL,
  `value` TEXT,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
