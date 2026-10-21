<?php
$pd = $GLOBALS['PAGE_DATA'] ?? [];
$pageTitle = $pd['title'] ?? 'Admin';
$me = currentUser();
$flashes = takeFlash();
require_once BASE_PATH . '/app/admin_resources.php';
$resList = adminResources();

// Sidebar entries, grouped.
$nav = ['Overview' => [
    ['Dashboard', '', 'dashboard', 0],
    ['Members',   'members',  'users',  (int) fetchValue("SELECT COUNT(*) FROM members WHERE status='pending'", [], 0)],
    ['Payments',  'payments', 'wallet', (int) fetchValue("SELECT COUNT(*) FROM payments WHERE status='pending'", [], 0)],
    ['Messages',  'messages', 'mail',   (int) fetchValue('SELECT COUNT(*) FROM contacts WHERE is_read = 0', [], 0)],
]];
foreach ($resList as $key => $r) {
    $nav[$r['group']][] = [$r['label'], $key, $r['icon'], 0];
}
$nav['Settings'][] = ['Site Settings', 'settings', 'settings', 0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> · <?= e(SITE_SHORT) ?> Admin</title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
<script>
(function () {
  try {
    var t = localStorage.getItem('duetcs-theme');
    if (t !== 'light' && t !== 'dark') t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', t);
    document.documentElement.classList.add('js');
  } catch (e) {}
})();
</script>
</head>
<body>
<div class="admin-shell">

  <aside class="admin-side" id="admin-side">
    
<a class="brand" href="<?= url() ?>">
  <span class="brand-mark">
    <img
      src="<?= asset('img/logo.png') ?>"
      alt="DUETCS Logo"
      class="admin-logo"
    >
  </span>
  <span class="brand-text">
    <span class="brand-name"><?= e(SITE_SHORT) ?></span>
    <span class="brand-sub">Admin panel</span>
  </span>
</a>


    <nav class="admin-nav" aria-label="Admin sections">
      <?php foreach ($nav as $group => $items): ?>
        <div class="admin-nav-group"><?= e($group) ?></div>
        <?php foreach ($items as [$label, $slug, $ic, $badge]):
            $path = 'admin' . ($slug ? '/' . $slug : '');
            $active = $slug === '' ? isActive('admin', true) : isActive($path); ?>
          <a href="<?= url($path) ?>" class="<?= $active ? 'is-active' : '' ?>">
            <?= icon($ic) ?><span><?= e($label) ?></span>
            <?php if ($badge > 0): ?><span class="pill"><?= $badge ?></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </nav>

    <div class="admin-side-foot">
      <a class="btn btn-ghost btn-block btn-sm" href="<?= url() ?>" target="_blank"><?= icon('external') ?> View website</a>
      <a class="btn btn-ghost btn-block btn-sm" style="margin-top:.5rem" href="<?= url('logout') ?>"><?= icon('logout') ?> Sign out</a>
    </div>
  </aside>

  <div class="admin-main">
    <header class="admin-top">
      <button class="icon-btn admin-side-toggle" type="button" aria-label="Toggle sidebar"><?= icon('menu') ?></button>
      <h1><?= e($pageTitle) ?></h1>
      <div class="spacer"></div>
      <button class="icon-btn theme-toggle" type="button" aria-label="Toggle colour theme">
        <span class="icon-sun"><?= icon('sun') ?></span>
        <span class="icon-moon"><?= icon('moon') ?></span>
      </button>
      <span class="badge badge-brand" title="<?= e($me['email']) ?>">
        <?= icon('users') ?> <?= e($me['name']) ?>
      </span>
    </header>

    <?php if ($flashes): ?>
      <div class="flash-stack" role="status" aria-live="polite">
        <?php foreach ($flashes as $f):
            $map = ['success' => 'check', 'error' => 'close', 'warning' => 'sparkle', 'info' => 'sparkle']; ?>
          <div class="toast toast-<?= e($f['type']) ?>">
            <?= icon($map[$f['type']] ?? 'sparkle') ?>
            <span><?= e($f['message']) ?></span>
            <button class="toast-close" type="button" aria-label="Dismiss"><?= icon('close') ?></button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="admin-body">
