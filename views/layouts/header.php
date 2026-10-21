<?php

/** Site layout — opening half. */

$pd        = $GLOBALS['PAGE_DATA'] ?? [];
$pageTitle = $pd['title'] ?? SITE_NAME;
$pageDesc  = $pd['description'] ?? SITE_DESC;
$navItems  = require BASE_PATH . '/views/partials/navdata.php';

$me      = currentUser();
$flashes = takeFlash();

?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    <?= e($pageTitle === SITE_NAME ? SITE_NAME . ' - ' . SITE_TAGLINE : $pageTitle . ' · ' . SITE_SHORT) ?>
  </title>

  <meta name="description" content="<?= e($pageDesc) ?>">

  <meta name="theme-color" content="#4f46e5">

  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">

  <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap"
    rel="stylesheet"
  >

  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">

  <script>

    /* Apply the saved theme before first paint so the page never flashes. */

    (function () {

      try {

        var t = localStorage.getItem('duetcs-theme');

        if (t !== 'light' && t !== 'dark') {
          t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        document.documentElement.setAttribute('data-theme', t);
        document.documentElement.classList.add('js');

      } catch (e) {}

    })();

  </script>

</head>

<body>

<a class="skip-link" href="#main">Skip to content</a>

<div class="progress-bar" aria-hidden="true"></div>


<!-- ==============================================================
     Top utility bar
     ============================================================== -->

<div class="topbar">

  <div class="container">

    <div class="topbar-links">

      <a href="mailto:<?= e(setting('site_email', SITE_EMAIL)) ?>">
        <?= icon('mail') ?>
        <span><?= e(setting('site_email', SITE_EMAIL)) ?></span>
      </a>

      <a
        class="hide-md"
        href="tel:<?= e(preg_replace('/\s+/', '', setting('site_phone', SITE_PHONE))) ?>"
      >
        <?= icon('phone') ?>
        <span><?= e(setting('site_phone', SITE_PHONE)) ?></span>
      </a>

    </div>


    <div class="topbar-links">

      <a
        class="hide-md"
        href="<?= url('certificates') ?>"
      >
        <?= icon('award') ?>
        <span>Verify Certificate</span>
      </a>

      <a
        href="<?= e(setting('facebook_url', '#')) ?>"
        target="_blank"
        rel="noopener"
        aria-label="Facebook"
      >
        <?= icon('facebook') ?>
      </a>

      <a
        href="<?= e(setting('github_url', '#')) ?>"
        target="_blank"
        rel="noopener"
        aria-label="GitHub"
      >
        <?= icon('github') ?>
      </a>

      <a
        href="<?= e(setting('linkedin_url', '#')) ?>"
        target="_blank"
        rel="noopener"
        aria-label="LinkedIn"
      >
        <?= icon('linkedin') ?>
      </a>

    </div>

  </div>

</div>


<!-- ==============================================================
     Primary header
     ============================================================== -->

<header class="site-header">

  <div class="container">

    <nav class="nav" aria-label="Primary">


      <!-- ========================================================
           DUETCS BRAND
           Custom logo is used here
           ======================================================== -->

      <a class="brand" href="<?= url() ?>">

        <span class="brand-mark">
          <img
            src="<?= asset('img/logo.png') ?>"
            alt="DUETCS Logo"
          >
        </span>

        <span class="brand-text">

          <span class="brand-name">
            <?= e(SITE_SHORT) ?>
          </span>

          <span class="brand-sub">
            Computer Society
          </span>

        </span>

      </a>


      <!-- ========================================================
           Desktop navigation
           ======================================================== -->

      <ul class="menu">

        <?php foreach ($navItems as $item): ?>

          <li class="<?= isActive($item['url'], empty($item['children']) && $item['url'] === '') ? 'is-active' : '' ?>">

            <a
              class="menu-link"
              href="<?= url($item['url']) ?>"
            >

              <?= e($item['label']) ?>

              <?php if (!empty($item['children'])): ?>

                <svg
                  class="chev"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.4"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  aria-hidden="true"
                >
                  <polyline points="6 9 12 15 18 9"/>
                </svg>

              <?php endif; ?>

            </a>


            <?php if (!empty($item['children'])): ?>

              <ul class="submenu <?= !empty($item['wide']) ? 'submenu-wide' : '' ?>">

                <?php foreach ($item['children'] as $child): ?>

                  <li>

                    <a
                      href="<?= url($child['url']) ?>"
                      class="<?= isActive($child['url'], true) ? 'is-active' : '' ?>"
                    >

                      <?= icon($child['icon'] ?? 'arrow') ?>

                      <span>
                        <?= e($child['label']) ?>
                      </span>

                    </a>

                  </li>

                <?php endforeach; ?>

              </ul>

            <?php endif; ?>

          </li>

        <?php endforeach; ?>

      </ul>


      <!-- ========================================================
           Navigation actions
           ======================================================== -->

      <div class="nav-actions">


        <!-- Theme button -->

        <button
          class="icon-btn theme-toggle"
          type="button"
          aria-label="Toggle colour theme"
        >

          <span class="icon-sun">
            <?= icon('sun') ?>
          </span>

          <span class="icon-moon">
            <?= icon('moon') ?>
          </span>

        </button>


        <!-- Dashboard / Login -->

        <?php if ($me): ?>

          <a
            class="icon-btn"
            href="<?= url(isAdmin() ? 'admin' : 'dashboard') ?>"
            aria-label="Your dashboard"
          >
            <?= icon('dashboard') ?>
          </a>

        <?php else: ?>

          <a
            class="icon-btn"
            href="<?= url('login') ?>"
            aria-label="Sign in"
          >
            <?= icon('users') ?>
          </a>

        <?php endif; ?>


        <!-- Join Us -->

        <a
          class="btn btn-sm hide-sm"
          href="<?= url('membership') ?>"
        >
          <?= icon('sparkle') ?>
          Join Us
        </a>


        <!-- Mobile menu button -->

        <button
          class="icon-btn menu-toggle"
          type="button"
          aria-label="Open menu"
          aria-expanded="false"
          aria-controls="drawer"
        >
          <?= icon('menu') ?>
        </button>

      </div>

    </nav>

  </div>

</header>


<!-- ==============================================================
     Mobile drawer
     ============================================================== -->

<div class="overlay" id="overlay"></div>


<aside
  class="drawer"
  id="drawer"
  aria-label="Mobile navigation"
>


  <div class="drawer-head">

    <!-- Mobile logo -->

    <a class="brand" href="<?= url() ?>">

      <span class="brand-mark">

        <img
          src="<?= asset('img/logo.png') ?>"
          alt="DUETCS Logo"
        >

      </span>

      <span class="brand-text">

        <span class="brand-name">
          <?= e(SITE_SHORT) ?>
        </span>

      </span>

    </a>


    <!-- Close button -->

    <button
      class="icon-btn menu-toggle"
      type="button"
      aria-label="Close menu"
    >
      <?= icon('close') ?>
    </button>

  </div>


  <!-- Mobile navigation -->

  <div class="drawer-body">

    <?php foreach ($navItems as $item): ?>

      <?php if (empty($item['children'])): ?>

        <a
          href="<?= url($item['url']) ?>"
          class="<?= isActive($item['url'], $item['url'] === '') ? 'is-active' : '' ?>"
        >
          <?= e($item['label']) ?>
        </a>

      <?php else: ?>

        <div
          class="drawer-group <?= isActive($item['url']) ? 'is-open' : '' ?>"
        >

          <button
            type="button"
            aria-expanded="<?= isActive($item['url']) ? 'true' : 'false' ?>"
          >

            <?= e($item['label']) ?>

            <svg
              class="chev"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2.4"
              stroke-linecap="round"
              stroke-linejoin="round"
              aria-hidden="true"
            >
              <polyline points="6 9 12 15 18 9"/>
            </svg>

          </button>


          <div class="drawer-sub">

            <?php foreach ($item['children'] as $child): ?>

              <a
                href="<?= url($child['url']) ?>"
                class="<?= isActive($child['url'], true) ? 'is-active' : '' ?>"
              >
                <?= e($child['label']) ?>
              </a>

            <?php endforeach; ?>

          </div>

        </div>

      <?php endif; ?>

    <?php endforeach; ?>

  </div>


  <!-- Mobile drawer footer -->

  <div class="drawer-foot">

    <a
      class="btn btn-block"
      href="<?= url('membership') ?>"
    >
      <?= icon('sparkle') ?>
      Become a Member
    </a>


    <?php if ($me): ?>

      <a
        class="btn btn-ghost btn-block"
        style="margin-top:.6rem"
        href="<?= url(isAdmin() ? 'admin' : 'dashboard') ?>"
      >
        <?= icon('dashboard') ?>
        Dashboard
      </a>

    <?php else: ?>

      <a
        class="btn btn-ghost btn-block"
        style="margin-top:.6rem"
        href="<?= url('login') ?>"
      >
        <?= icon('users') ?>
        Sign In
      </a>

    <?php endif; ?>

  </div>

</aside>


<!-- ==============================================================
     Flash messages
     ============================================================== -->

<?php if ($flashes): ?>

  <div
    class="flash-stack"
    role="status"
    aria-live="polite"
  >

    <?php foreach ($flashes as $f):

      $map = [
        'success' => 'check',
        'error'   => 'close',
        'warning' => 'sparkle',
        'info'    => 'sparkle'
      ];

    ?>

      <div class="toast toast-<?= e($f['type']) ?>">

        <?= icon($map[$f['type']] ?? 'sparkle') ?>

        <span>
          <?= e($f['message']) ?>
        </span>

        <button
          class="toast-close"
          type="button"
          aria-label="Dismiss"
        >
          <?= icon('close') ?>
        </button>

      </div>

    <?php endforeach; ?>

  </div>

<?php endif; ?>


<main id="main">