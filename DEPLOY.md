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

To debug a failing deployment, set `DUETCS_DEBUG=1` temporarily. It prints the
real PDO error instead of "Database unavailable." **Remove it once the site
works**: it exposes your database host and user to anyone visiting the page.

## Step 5 — Verify

- The home page loads with images.
- A committee or gallery page shows photos (proves `uploads/` is readable).
- Signing in works, and you are *still* signed in on the next page (proves
  database sessions work).
- `/install.php` returns 404. It must: that script drops every table and
  creates an admin account, so it is deliberately unreachable through
  `vercel.json`.

## Known limitation: new uploads

A serverless instance cannot write files, so creating an event or committee
member **with a new image** will report that the filesystem is read-only. The
rest of the record saves normally.

To add images on Vercel, commit them to `uploads/<folder>/` in the repository
and reference the filename in the admin form. For a fully working admin panel,
either host the site somewhere with a persistent disk (Railway, Render, Fly.io
— the existing `Dockerfile` runs as-is), or move uploads to object storage
such as Vercel Blob, S3 or Cloudinary.
