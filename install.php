<?php
/**
 * DUETCS installer — run once, then delete this file.
 *
 * Creates the database, loads the schema and the seed content, and creates the
 * admin account. Accounts are created here (rather than in the .sql file) so
 * that passwords are hashed by PHP's own password_hash() and no hash is ever
 * hard-coded anywhere in the project.
 */
require __DIR__ . '/config/config.php';

$step   = $_POST['step'] ?? 'start';
$errors = [];
$log    = [];
$done   = false;

/* ---------------------------------------------------------------- checks */
$checks = [
    'PHP 8.0 or newer'          => [version_compare(PHP_VERSION, '8.0.0', '>='), PHP_VERSION],
    'PDO MySQL driver'          => [extension_loaded('pdo_mysql'), extension_loaded('pdo_mysql') ? 'loaded' : 'missing'],
    'fileinfo extension'        => [extension_loaded('fileinfo'), extension_loaded('fileinfo') ? 'loaded' : 'missing'],
    'mbstring extension'        => [extension_loaded('mbstring'), extension_loaded('mbstring') ? 'loaded' : 'missing'],
    'uploads/ is writable'      => [is_writable(__DIR__ . '/uploads'), is_writable(__DIR__ . '/uploads') ? 'writable' : 'not writable'],
    'database/schema.sql found' => [is_file(__DIR__ . '/database/schema.sql'), is_file(__DIR__ . '/database/schema.sql') ? 'found' : 'missing'],
    'database/seed.sql found'   => [is_file(__DIR__ . '/database/seed.sql'), is_file(__DIR__ . '/database/seed.sql') ? 'found' : 'missing'],
];
$ready = !in_array(false, array_column($checks, 0), true);

/**
 * Split a .sql file into statements without breaking on semicolons that appear
 * inside string literals — the seed content is full of them.
 */
function splitSql(string $sql): array
{
    $statements = [];
    $current    = '';
    $len        = strlen($sql);
    $i          = 0;

    while ($i < $len) {
        $ch = $sql[$i];

        // line comments
        if (($ch === '-' && substr($sql, $i, 2) === '--') || $ch === '#') {
            $nl = strpos($sql, "\n", $i);
            $i  = $nl === false ? $len : $nl + 1;
            continue;
        }
        // block comments
        if ($ch === '/' && substr($sql, $i, 2) === '/*') {
            $end = strpos($sql, '*/', $i + 2);
            $i   = $end === false ? $len : $end + 2;
            continue;
        }
        // quoted strings and identifiers
        if ($ch === "'" || $ch === '"' || $ch === '`') {
            $quote    = $ch;
            $current .= $ch;
            $i++;
            while ($i < $len) {
                $c = $sql[$i];
                if ($c === '\\' && $quote !== '`') {          // backslash escape
                    $current .= substr($sql, $i, 2);
                    $i += 2;
                    continue;
                }
                if ($c === $quote) {
                    if ($i + 1 < $len && $sql[$i + 1] === $quote) {  // '' -> literal quote
                        $current .= $quote . $quote;
                        $i += 2;
                        continue;
                    }
                    $current .= $quote;
                    $i++;
                    break;
                }
                $current .= $c;
                $i++;
            }
            continue;
        }
        if ($ch === ';') {
            if (trim($current) !== '') {
                $statements[] = trim($current);
            }
            $current = '';
            $i++;
            continue;
        }
        $current .= $ch;
        $i++;
    }

    if (trim($current) !== '') {
        $statements[] = trim($current);
    }
    return $statements;
}

/* ----------------------------------------------------------- run install */
if ($step === 'install' && $ready) {
    $adminName  = trim($_POST['admin_name'] ?? '');
    $adminUser  = trim($_POST['admin_username'] ?? '');
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPass  = (string) ($_POST['admin_password'] ?? '');
    $withSeed   = !empty($_POST['with_seed']);
    $withDemo   = !empty($_POST['with_demo']);

    if (strlen($adminName) < 3)                                  $errors[] = 'Enter the administrator\'s full name.';
    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $adminUser))       $errors[] = 'Username must be 3-30 letters, numbers or underscores.';
    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL))         $errors[] = 'Enter a valid administrator email address.';
    if (strlen($adminPass) < 8)                                  $errors[] = 'The admin password must be at least 8 characters.';

    if (!$errors) {
        try {
            // 1. connect to the server and create the database
            $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            $log[] = 'Database `' . DB_NAME . '` is ready.';

            $pdo->exec('USE `' . DB_NAME . '`');
            $pdo->exec('SET NAMES utf8mb4');

            // 2. schema
            $n = 0;
            foreach (splitSql(file_get_contents(__DIR__ . '/database/schema.sql')) as $stmt) {
                $pdo->exec($stmt);
                $n++;
            }
            $log[] = "Schema loaded — {$n} statements, 23 tables created.";

            // 3. seed content
            if ($withSeed) {
                $n = 0;
                foreach (splitSql(file_get_contents(__DIR__ . '/database/seed.sql')) as $stmt) {
                    $pdo->exec($stmt);
                    $n++;
                }
                $log[] = "Sample content loaded — {$n} statements.";
            }

            // 4. accounts, hashed by PHP
            $ins = $pdo->prepare(
                'INSERT INTO users (name, username, email, password, role, status)
                 VALUES (?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role), name = VALUES(name)'
            );
            $ins->execute([$adminName, $adminUser, $adminEmail, password_hash($adminPass, PASSWORD_DEFAULT), 'admin', 'active']);
            $log[] = 'Administrator account created: ' . htmlspecialchars($adminUser);

            if ($withDemo) {
                $ins->execute(['Demo Student', 'student', 'student@duet.ac.bd', password_hash('student123', PASSWORD_DEFAULT), 'user']);
                $log[] = 'Demo student account created: student / student123';
            }

            $done = true;
        } catch (Throwable $ex) {
            $errors[] = 'Installation failed: ' . $ex->getMessage();
        }
    }
}

$defaults = [
    'admin_name'     => $_POST['admin_name']     ?? 'DUETCS Administrator',
    'admin_username' => $_POST['admin_username'] ?? 'admin',
    'admin_email'    => $_POST['admin_email']    ?? 'admin@duet.ac.bd',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Install · DUET Computer Society</title>
<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/main.css">
<style>
  body { display: grid; place-items: center; min-height: 100vh; padding: 2.5rem 1.25rem; }
  .install { width: min(100%, 46rem); }
  .install-head { text-align: center; margin-bottom: 2rem; }
  .check-row {
    display: flex; align-items: center; gap: .8rem;
    padding: .65rem .9rem; border-radius: var(--radius-sm);
    background: var(--surface-2); font-size: var(--step-0);
  }
  .check-row + .check-row { margin-top: .45rem; }
  .check-row .state { margin-left: auto; font-family: var(--font-mono); font-size: var(--step--1); color: var(--text-mute); }
  .ok   { color: var(--emerald-500); }
  .fail { color: var(--rose-500); }
  .log li { display: flex; gap: .6rem; padding: .4rem 0; font-size: var(--step-0); color: var(--text-soft); }
  .log .icon { color: var(--emerald-500); flex: none; margin-top: .2rem; }
</style>
</head>
<body>
<div class="install">

  <div class="install-head">
    <span style="display:inline-grid;place-items:center;width:64px;height:64px;border-radius:19px;background:var(--gradient-brand);color:#fff;margin-bottom:1rem">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
    </span>
    <h1 style="font-size:var(--step-4)">DUETCS Installer</h1>
    <p class="soft" style="font-size:var(--step-1);margin-top:.5rem">Sets up the database and your administrator account.</p>
  </div>

  <?php if ($done): ?>
    <div class="card" style="padding:2rem">
      <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.4rem">
        <span style="display:grid;place-items:center;width:56px;height:56px;border-radius:50%;background:var(--emerald-500);color:#fff;flex:none">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </span>
        <div>
          <h2 style="font-size:var(--step-2)">Installation complete</h2>
          <p class="muted">Your DUETCS website is ready to use.</p>
        </div>
      </div>

      <ul class="log" style="margin-bottom:1.6rem">
        <?php foreach ($log as $line): ?>
          <li>
            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            <span><?= htmlspecialchars($line) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="alert alert-warning">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none;margin-top:.15rem"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div>
          <strong>Delete <code>install.php</code> now.</strong>
          Leaving it in place lets anyone who finds it wipe your database and reset the admin password.
        </div>
      </div>

      <div class="btn-group" style="margin-top:1.5rem">
        <a class="btn btn-lg" href="index.php">Open the website</a>
        <a class="btn btn-ghost btn-lg" href="index.php?p=login">Sign in to admin</a>
      </div>
    </div>

  <?php else: ?>

    <?php foreach ($errors as $err): ?>
      <div class="alert alert-error">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none;margin-top:.15rem"><path d="M18 6 6 18M6 6l12 12"/></svg>
        <span><?= htmlspecialchars($err) ?></span>
      </div>
    <?php endforeach; ?>

    <div class="card" style="padding:1.9rem;margin-bottom:1.5rem">
      <h2 style="font-size:var(--step-2);margin-bottom:1.2rem">1 · Server requirements</h2>
      <?php foreach ($checks as $label => [$pass, $detail]): ?>
        <div class="check-row">
          <span class="<?= $pass ? 'ok' : 'fail' ?>">
            <?php if ($pass): ?>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            <?php else: ?>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            <?php endif; ?>
          </span>
          <span><?= htmlspecialchars($label) ?></span>
          <span class="state"><?= htmlspecialchars($detail) ?></span>
        </div>
      <?php endforeach; ?>

      <div class="alert alert-info" style="margin-top:1.2rem;margin-bottom:0">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none;margin-top:.15rem"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <div>
          Connecting as <code><?= htmlspecialchars(DB_USER) ?>@<?= htmlspecialchars(DB_HOST) ?></code>
          to database <code><?= htmlspecialchars(DB_NAME) ?></code>.
          Change these in <code>config/config.php</code> if your MySQL uses different credentials.
        </div>
      </div>
    </div>

    <form method="post" class="card" style="padding:1.9rem">
      <input type="hidden" name="step" value="install">
      <h2 style="font-size:var(--step-2);margin-bottom:.4rem">2 · Administrator account</h2>
      <p class="muted" style="margin-bottom:1.5rem;font-size:var(--step-0)">
        This account can manage every part of the site. The password is hashed with bcrypt before it is stored.
      </p>

      <div class="form-row">
        <div class="field">
          <label for="admin_name">Full name</label>
          <input class="input" type="text" id="admin_name" name="admin_name" required value="<?= htmlspecialchars($defaults['admin_name']) ?>">
        </div>
        <div class="field">
          <label for="admin_username">Username</label>
          <input class="input" type="text" id="admin_username" name="admin_username" required value="<?= htmlspecialchars($defaults['admin_username']) ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="field">
          <label for="admin_email">Email</label>
          <input class="input" type="email" id="admin_email" name="admin_email" required value="<?= htmlspecialchars($defaults['admin_email']) ?>">
        </div>
        <div class="field">
          <label for="admin_password">Password</label>
          <input class="input" type="password" id="admin_password" name="admin_password" required minlength="8" placeholder="At least 8 characters">
          <span class="field-hint">Choose something you will remember - there is no password reset by email yet.</span>
        </div>
      </div>

      <h2 style="font-size:var(--step-2);margin:1.8rem 0 1rem">3 · Content</h2>

      <label class="check" style="margin-bottom:.8rem">
        <input type="checkbox" name="with_seed" value="1" checked>
        <span>
          <b>Load the sample content</b> - events, committee, activities, competitions, projects, blog posts,
          gallery, resources, certificates and membership plans. Recommended; you can edit or delete any of it later.
        </span>
      </label>

      <label class="check">
        <input type="checkbox" name="with_demo" value="1" checked>
        <span><b>Create a demo student account</b> (<code>student</code> / <code>student123</code>) for testing the membership flow.</span>
      </label>

      <div class="alert alert-warning" style="margin-top:1.5rem">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:none;margin-top:.15rem"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div><strong>This drops and recreates every DUETCS table.</strong> Any existing data in the <code><?= htmlspecialchars(DB_NAME) ?></code> database will be lost.</div>
      </div>

      <button class="btn btn-lg btn-block" type="submit" style="margin-top:1.5rem" <?= $ready ? '' : 'disabled' ?>>
        <?= $ready ? 'Install DUETCS' : 'Fix the failing requirements first' ?>
      </button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>
