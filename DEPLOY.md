# Deploying DUETCS to Vercel

## Why this needed work

Vercel runs serverless functions. This project is a PHP + MySQL + Apache app,
so five things had to be dealt with before it could run there:

| Problem | Fix |
|---|---|
| Vercel has no PHP runtime, and no `vercel.json` told it what the project was | `vercel.json` declares the community runtime `vercel-php@0.9.0` (PHP 8.5) |
| Vercel hosts no MySQL | You provision one externally and point the app at it with environment variables |
| `.htaccess` rewrites are ignored | Translated into the `routes` block of `vercel.json` |
| File-based sessions do not survive between requests, so logins drop at random | Sessions move to a MySQL table (`app/session_db.php`) when running serverless |
| The filesystem is read-only, so `uploads/` cannot be written | Images in the repository are served normally; new admin uploads fail with a clear message |

Nothing here changes how the project behaves on XAMPP or Docker. Everything is
switched by the `SERVERLESS` constant, which is only true when Vercel's own
`VERCEL` environment variable is present.

## Step 1 — Create a MySQL database

Vercel will not give you one. Any MySQL host with a public endpoint works;
[Aiven's free MySQL plan](https://aiven.io/free-tier) needs no credit card and
is enough for this project. TiDB Cloud Starter and Railway also work.

Note the **host**, **port**, **database name**, **user** and **password**.
Managed providers use a non-standard port and require TLS — both are now
supported, but you have to pass them in (Step 4).

## Step 2 — Import your content

Your live content lives in the Docker database, not in `database/seed.sql`.
Export it:

```bash
docker exec duetcs-db-1 mariadb-dump -uroot --single-transaction \
  --skip-lock-tables --no-tablespaces --default-character-set=utf8mb4 \
  --add-drop-table duetcs > database/duetcs-live.sql
```

That file holds password hashes and members' personal details, so it is
gitignored — never commit it.

Then load it into the database from Step 1, using the client inside the Docker
container (this project has no local `mysql` binary):

```bash
docker exec -i duetcs-db-1 mariadb --host=YOUR_HOST --port=YOUR_PORT \
  --user=YOUR_USER --password=YOUR_PASSWORD --ssl \
  YOUR_DB_NAME < database/duetcs-live.sql
```

The dump already contains the `sessions` table that serverless logins need.

## Step 3 — Deploy

```bash
npm i -g vercel
vercel login
vercel --prod
```

Or import the GitHub repository at [vercel.com/new](https://vercel.com/new).
Leave the framework preset as **Other** — `vercel.json` does the work. Do not
set a build command or an output directory.

The repository is connected, so a push to `main` deploys on its own; `vercel
--prod` from this folder still works and is the way to release without pushing.
Note that Vercel detected the `Dockerfile` and set the framework preset to
**Container**, so every deploy also builds a ~150MB container image that serves
no traffic — the PHP function answers requests. It costs build time, nothing
more.

## Step 4 — Environment variables

In Vercel → your project → **Settings → Environment Variables**, add:

| Name | Value |
|---|---|
| `DUETCS_DB_HOST` | your database host |
| `DUETCS_DB_PORT` | your database port |
| `DUETCS_DB_NAME` | your database name |
| `DUETCS_DB_USER` | your database user |
| `DUETCS_DB_PASS` | your database password |
| `DUETCS_DB_SSL` | `1` |

Redeploy afterwards — environment variables are read at request time, but a
deployment created before they existed will not pick them up.

To debug a failing deployment, set `DUETCS_DEBUG=1` temporarily. Instead of
"Database unavailable." the error page then shows the real PDO error plus the
host, port, database, user and TLS mode the connection was attempted with, and
whether a password was supplied. If those read `localhost` / `duetcs` / `root`,
the environment variables never reached the app — check that they are enabled
for the **Production** environment and that you redeployed after adding them.

**Remove `DUETCS_DEBUG` once the site works**: it exposes your database host
and user to anyone visiting the page.

## Step 5 — Verify

- The home page loads with images.
- A committee or gallery page shows photos (proves `uploads/` is readable).
- Signing in works, and you are *still* signed in on the next page (proves
  database sessions work).
- `/install.php` returns 404. It must: that script drops every table and
  creates an admin account, so it is deliberately unreachable through
  `vercel.json`.

## Things that actually went wrong, and why

Worth knowing before changing any of this.

**Managed MySQL runs with `ANSI_QUOTES`.** Under that sql_mode a double-quoted
token in SQL is an *identifier*, not a string, so `status = "active"` fails with
"Unknown column 'active'". The local MariaDB does not set it, so queries written
that way work in development and break on Aiven. Bind values as parameters; the
project's own rule already says so. To check nothing has crept back in:

```bash
docker run --rm -v "$PWD:/app:ro" php:8.5-cli php -r 'foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/app")) as $f) { if (!$f->isFile() || $f->getExtension()!=="php") continue; foreach (@token_get_all(file_get_contents($f->getPathname())) ?: [] as $t) { if (is_array($t) && $t[0]===T_CONSTANT_ENCAPSED_STRING && preg_match("/\b(SELECT|INSERT INTO|UPDATE|WHERE|JOIN)\b/i",$t[1]) && preg_match_all("/\"[A-Za-z_][A-Za-z0-9_ ]*\"/",$t[1],$m)) foreach($m[0] as $l) printf("%s:%d %s\n",$f->getPathname(),$t[2],$l); } }'
```

**Vercel runs PHP 8.5, Docker runs 8.2.** Code that is merely deprecated on 8.5
still *prints* a notice, and output before `session_start()` breaks the session
cookie and then the page. That is how `PDO::MYSQL_ATTR_SSL_*` took the site down.
Test against 8.5 before deploying, not just against the container.

**Vercel does not publish this repository as static files.** Requests for
`/assets/...` reach the function through the catch-all rewrite instead, so
`api/index.php` serves them from the function bundle. Anything new that has to
be served as a file must live under `assets/` or `uploads/` and have a media
type listed there.

**Only the last route in `vercel.json` is honoured here.** Every route before
the `filesystem` phase was observed to be skipped, so source files are kept
unreachable by the front controller's own whitelist rather than by those rules.
Do not rely on a `vercel.json` route to block anything.

**Keep the function in the database's region.** `vercel.json` pins it to
`sin1`, because the Aiven service resolves to a Singapore address. It defaulted
to `iad1` (Washington DC), and with the home page issuing about a dozen queries,
each paying a round trip across the planet, a page took six seconds; from `sin1`
it takes about four hundred milliseconds. If the database is ever moved, move
this with it — `dig +short <aiven-host>` and geolocating the address is enough
to tell where it lives.

**Never silence errors.** `error_reporting(0)` turned a fatal into a blank white
page with nothing in any log, which is the hardest failure to diagnose on a host
whose filesystem you cannot open. Display is off in production; logging is not.

## Uploads

A serverless instance cannot write to its own filesystem, so uploaded images go
to a Vercel Blob store (`duetcs-uploads`, linked to the project) and the
database keeps the URL that comes back. `BLOB_READ_WRITE_TOKEN` is set on the
deployment automatically by that link — nothing to configure by hand.

This needed no change outside `app/blob.php` and two functions in
`app/helpers.php`, because `upload()` already returned an absolute URL
untouched: an image column can hold either a bare filename (everything
committed under `uploads/`) or a Blob URL (anything uploaded since), and views
treat both the same.

Locally the token is absent, `BLOB_ENABLED` is false, and uploads go to
`uploads/` exactly as before.

**The store is public.** The URLs carry a random name, so they are not
guessable, but anyone holding one can open it — the same exposure the images
have on the site itself. That is fine for committee photos and event banners.
It is worth a thought for `uploads/payments/`, where membership applicants
upload a payment screenshot that may show their name, phone number and
transaction id. If those should not sit in public storage, the options are a
second store created with `--access private` and served through signed URLs, or
keeping that one flow on a host with a real disk.
