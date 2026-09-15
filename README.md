# DUET Computer Society (DUETCS)

The official website of the DUET Computer Society — Dhaka University of Engineering
& Technology, Gazipur.

Built with **PHP 8 + MySQL** on a hand-written MVC-style structure. No framework,
no Composer, no build step: unzip it into `htdocs`, run the installer, done.

---

## Quick start (XAMPP)

1. Copy the `duetcs` folder into your web root:
   - Windows — `C:\xampp\htdocs\duetcs`
   - macOS — `/Applications/XAMPP/htdocs/duetcs`
   - Linux — `/opt/lampp/htdocs/duetcs`
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open <http://localhost/duetcs/install.php> and complete the one-page installer.
   It creates the database, loads the schema and the sample content, and creates
   your administrator account.
4. **Delete `install.php`.** It can wipe the database, so it must not stay on a
   live server.
5. Open <http://localhost/duetcs/>.

If your MySQL uses a password (or a different database name), edit
`config/config.php` **before** running the installer:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'duetcs');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Importing the SQL by hand instead

If you would rather use phpMyAdmin: create a database named `duetcs`, import
`database/schema.sql`, then `database/seed.sql`. You still need to run
`install.php` afterwards to create the login accounts — passwords are hashed by
PHP at install time and are deliberately not stored in the `.sql` files.

---

## Running from VS Code

VS Code is an editor, not a server. **Live Server does not work for this
project** — it only serves static files, so a `.php` file would appear as raw
code or download instead of running. You need PHP and MySQL running alongside
VS Code. Pick one of the two setups below.

### Option A — Docker (nothing to install if you already have Docker Desktop)

1. Open the `duetcs` folder in VS Code (**File → Open Folder**).
2. Press **Cmd/Ctrl + Shift + B**, or open the Command Palette
   (**Cmd/Ctrl + Shift + P** → *Tasks: Run Task*) and choose
   **Start DUETCS (Docker)**.
   The equivalent terminal command is:

   ```bash
   docker compose up --build
   ```
3. Open <http://localhost:8080/install.php> and complete the installer.
4. The site is then at <http://localhost:8080/>, and phpMyAdmin — for browsing
   the tables — is at <http://localhost:8081/>.

Your folder is mounted live, so **every file you save in VS Code appears on the
next browser refresh**. No rebuild, no restart.

Other tasks in the same menu: **Stop DUETCS (Docker)** (`docker compose down`),
and **Reset DUETCS database** if you want to wipe it and install again.

### Option B — XAMPP (what a grader will most likely use)

1. Install XAMPP from <https://www.apachefriends.org/>.
2. Put the `duetcs` folder inside `htdocs` (see paths in Quick start above).
3. Start **Apache** and **MySQL** in the XAMPP control panel.
4. In VS Code, open the same folder from inside `htdocs` — so you edit exactly
   the files Apache is serving.
5. Open <http://localhost/duetcs/install.php>, then <http://localhost/duetcs/>.

No configuration is needed: `config/config.php` defaults to the standard XAMPP
setup (`localhost`, user `root`, empty password). Docker overrides those through
environment variables, so the same file works for both without editing.

### Recommended extensions

Opening the folder prompts you to install these (from `.vscode/extensions.json`):

- **PHP Intelephense** — autocomplete, go-to-definition, error squiggles
- **Docker** — start and stop the containers from the sidebar
- **HTML CSS Support** — CSS class autocomplete inside PHP templates

---

## Signing in

The installer asks you to choose the administrator username and password.
If you ticked "create a demo student account", you also get:

| Role    | Username  | Password     |
|---------|-----------|--------------|
| Student | `student` | `student123` |

Change or delete the demo account before deploying anywhere real.

---

## What is in the site

### Public pages

```
Home
About            About DUETCS · History · Mission & Vision · Constitution
Committee        Current · Previous committees · Advisors
Events           All · Upcoming · Ongoing · Past  (+ detail pages)
Activities       Programming · AI & ML · Cybersecurity · Development ·
                 Workshops · Seminars              (+ detail pages)
Competitions     IUPC · Intra DUET · Hackathon · Other  (+ detail pages)
Projects         Filterable showcase               (+ case-study pages)
Blog             Categories, pagination, view counts (+ article pages)
Gallery          Photo albums with lightbox · Video gallery
Resources        Five categories, filterable by level
Membership       Plans · Application · Payment · Status
Certificates     Public verification by code
Contact          Message form, newsletter signup, map
Search           Across events, activities, competitions, projects, articles
```

Plus `login`, `register`, `dashboard`, `profile`, and a 404 page.

### Admin panel — `/admin`

- **Dashboard** — pending applications, the payment queue, verified income by
  month, recent messages
- **Members** — filter by status, approve, reject, add notes, delete
- **Payments** — verify or reject mobile-wallet transactions; verifying a
  payment approves the membership and issues a member number automatically
- **Messages** — contact submissions and the newsletter list
- **Content** — events, activities, competitions, projects, blog posts,
  photo albums, photos, videos, resources, certificates, committees,
  committee members, membership plans, payment methods, static pages,
  activity wings, resource categories
- **Settings** — contact details, social links, home-page statistics

---

## Membership payments (bKash · Nagad · Rocket)

Payments are **verified manually**, which is how student societies in Bangladesh
actually collect fees and needs no merchant API account:

1. The student picks a plan and fills in the application form.
2. The site shows the society's bKash / Nagad / Rocket number with
   step-by-step instructions for that wallet.
3. The student pays **from their own wallet app**, exactly as they would to any
   other number.
4. They enter the **Transaction ID** from the confirmation SMS, plus the number
   they paid from, and optionally a screenshot.
5. The Treasurer checks it against the wallet statement in the admin panel and
   clicks **Verify** — which approves the membership and assigns a member number.

**This application never handles a PIN, an OTP, a card number, or a wallet
password.** No money moves through the site. That is a deliberate design
decision, not a limitation: it removes the entire class of risk that comes with
storing payment credentials in a student project.

Guardrails built into the flow:

- `UNIQUE (method, trx_id)` in the database — the same transaction can never be
  submitted twice, by anyone
- The amount must match the selected plan exactly
- Sender numbers are validated against the Bangladeshi mobile format
- Rejecting a payment returns the applicant to "awaiting payment" so they can
  submit a corrected transaction ID without paying again

To go live, change the receiving numbers in **Admin → Payment Methods**.

If you later obtain a real bKash merchant account, the payment layer is isolated
in `app/controllers/membership.php` — a redirect-based checkout can be added
there without touching the rest of the application.

---

## Project layout

```
duetcs/
├── index.php               Front controller — every request enters here
├── install.php             One-time setup (delete after running)
├── .htaccess               Optional pretty URLs; blocks code in /uploads
├── docker-compose.yml      Optional: run the site without installing XAMPP
├── Dockerfile              Used only by docker-compose.yml
├── .vscode/                Run tasks and extension recommendations
│
├── config/
│   ├── config.php          Database credentials, site constants, base URL
│   └── database.php        PDO connection + query helpers
│
├── app/
│   ├── helpers.php         Escaping, URLs, uploads, dates, icons
│   ├── auth.php            Sessions, login, password hashing, access gates
│   ├── components.php      Reusable card/hero/pagination renderers
│   ├── admin_resources.php Declarative definition of every managed table
│   └── controllers/        One controller per section (16 files)
│
├── views/
│   ├── layouts/            Public and admin page shells
│   ├── partials/           Navigation tree
│   ├── pages/              Public page templates
│   └── admin/              Admin templates (incl. generic list + form)
│
├── assets/
│   ├── css/main.css        Design system — tokens, components, responsive
│   ├── css/admin.css       Admin panel styles
│   ├── js/main.js          Theme, navigation, reveals, lightbox, forms
│   └── img/                Logo, placeholders, generated album artwork
│
├── uploads/                Runtime uploads (must stay writable)
└── database/
    ├── schema.sql          23 tables
    └── seed.sql            Sample content
```

### How routing works

`index.php?p=events/upcoming` → first segment `events` is looked up in a
whitelist → `app/controllers/events.php` runs with `$params = ['upcoming']`.

Pretty URLs (`/events/upcoming`) work too: enable Apache's `mod_rewrite`, keep
the bundled `.htaccess`, and set `PRETTY_URLS` to `true` in `config/config.php`.
Every link on the site is generated through the `url()` helper, so both modes
work without editing templates.

### Adding a new managed content type

Add an entry to `adminResources()` in `app/admin_resources.php` describing the
table and its fields. The list view, create/edit form, validation, image
handling and delete confirmation are all generated from that definition.

---

## Security

This project was written to be safe to demonstrate and to read as an example.

| Concern | How it is handled |
|---|---|
| SQL injection | PDO prepared statements everywhere, native (not emulated). No user value is ever concatenated into SQL. |
| Password storage | `password_hash()` / `password_verify()` with bcrypt, plus automatic rehashing when the cost changes. |
| Session security | HttpOnly cookies, SameSite=Lax, session ID regenerated on login and logout. |
| CSRF | Every state-changing form carries a token checked with `hash_equals()`. |
| XSS | All dynamic output passes through `e()`. Rich text is admin-authored only. |
| File uploads | Real MIME type checked with `finfo`, extension whitelist, size cap, randomised filenames, and PHP execution disabled inside `/uploads`. |
| Brute force | Five failed logins locks the form for five minutes. |
| Access control | `requireLogin()` / `requireAdmin()` on every protected route; login failures never reveal which field was wrong. |
| Payment replay | Database-level uniqueness on `(method, trx_id)`. |

Before putting this on a public server: delete `install.php`, set `DEBUG` to
`false` in `config/config.php`, give MySQL a real password, create a dedicated
database user instead of `root`, and serve the site over HTTPS.

---

## Design

- Light and dark themes with a header toggle, remembered in `localStorage`,
  defaulting to the operating system preference. The theme is applied before
  first paint, so there is no flash of the wrong colours.
- Fluid typography and spacing via `clamp()` — no fixed breakpoint jumps in text.
- All body, heading, muted and navigation text meets **WCAG AA** contrast in both
  themes.
- Scroll-reveal animations, animated counters, and hover transitions, all
  disabled automatically under `prefers-reduced-motion`.
- Content is never hidden behind JavaScript: with JS disabled the whole site
  still renders and every page still works.
- Items with no uploaded image get a deterministic gradient derived from their
  title, so cards never look broken or repetitive.
- Icons are inline SVG — no icon-font CDN to fail.

Requires an internet connection only for Google Fonts and YouTube thumbnails;
both degrade gracefully offline.

---

## Requirements

- PHP 8.0 or newer, with `pdo_mysql`, `fileinfo` and `mbstring`
- MySQL 5.7+ or MariaDB 10.4+
- Apache (or any server that can run PHP)

All of these ship with a default XAMPP install.
