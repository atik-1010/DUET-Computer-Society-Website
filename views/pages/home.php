<?php /** Home page. */ ?>

<!-- ================================================================ hero -->
<section class="hero">
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="hero-grid" aria-hidden="true"></div>
  <div class="container">
    <div class="hero-inner">
      <div>
        <span class="hero-pill">
          <span class="tag">New</span>
          <b>Fall 2026 CP Bootcamp</b> - registration is open
        </span>

        <h1>Where DUET students<br><span class="gradient-text">build, break and win.</span></h1>

        <p class="hero-lead">
          <?= e(SITE_NAME) ?> is the official technology society of DUET, Gazipur.
          Competitive programming, AI, cybersecurity and software engineering -
          taught by students, open to every department, free to attend.
        </p>

        <div class="btn-group">
          <a class="btn btn-lg" href="<?= url('membership') ?>"><?= icon('sparkle') ?> Become a Member</a>
          <a class="btn btn-ghost btn-lg" href="<?= url('events/upcoming') ?>"><?= icon('calendar') ?> Upcoming Events</a>
        </div>

        <dl class="hero-stats">
          <div>
            <dt data-count="<?= (int) setting('stat_members', 480) ?>" data-suffix="+">0</dt>
            <dd>Active members</dd>
          </div>
          <div>
            <dt data-count="<?= (int) setting('stat_events', 60) ?>" data-suffix="+">0</dt>
            <dd>Events delivered</dd>
          </div>
          <div>
            <dt data-count="<?= (int) setting('stat_contests', 70) ?>">0</dt>
            <dd>Contests hosted</dd>
          </div>
          <div>
            <dt data-count="<?= (int) setting('stat_years', 15) ?>">0</dt>
            <dd>Years running</dd>
          </div>
        </dl>
      </div>
<div class="hero-visual">

    <div class="hero-slider">

        <img src="<?= asset('img/hero-1.jpg') ?>"
             class="hero-slide active"
             alt="DUETCS Event">

        <img src="<?= asset('img/hero-2.jpg') ?>"
             class="hero-slide"
             alt="DUETCS Event">

        <img src="<?= asset('img/hero-3.jpg') ?>"
             class="hero-slide"
             alt="DUETCS Event">

        <img src="<?= asset('img/hero-4.jpg') ?>"
             class="hero-slide"
             alt="DUETCS Event">

        <img src="<?= asset('img/hero-5.jpg') ?>"
             class="hero-slide"
             alt="DUETCS Event">

    </div>

</div>
</section>

<!-- keyword marquee -->
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    <?php
    $terms = ['Competitive Programming', 'Machine Learning', 'Capture The Flag', 'Web Development',
              'Data Structures', 'Cyber Security', 'Open Source', 'Hackathons', 'ICPC', 'Flutter',
              'PyTorch', 'System Design'];
    for ($i = 0; $i < 2; $i++) {
        foreach ($terms as $t) {
            echo '<span>' . icon('code') . e($t) . '</span>';
        }
    }
    ?>
  </div>
</div>

<!-- ========================================================== activities -->
<section class="section">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">What we do</span>
      <h2>Six wings. One society.</h2>
      <p>Each wing has its own lead on the Executive Committee, its own calendar, and its own way in for beginners.</p>
    </div>

    <div class="grid grid-3">
      <?php foreach ($categories as $c): ?>
        <a class="feature reveal" href="<?= url('activities/' . $c['slug']) ?>"
           style="--accent:linear-gradient(135deg, <?= e($c['accent']) ?>, <?= e($c['accent']) ?>bb); --accent-solid:<?= e($c['accent']) ?>">
          <span class="feature-icon"><?= icon($c['icon'], 'icon') ?></span>
          <h3><?= e($c['name']) ?></h3>
          <p><?= e($c['tagline']) ?></p>
          <span class="link">Explore <?= icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================== events -->
<?php if ($events): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal" style="display:flex;flex-wrap:wrap;align-items:end;justify-content:space-between;gap:1.5rem;max-width:none">
      <div style="max-width:40rem">
        <span class="eyebrow">On the calendar</span>
        <h2>What is happening next</h2>
        <p>Every session below is open to all DUET students. Membership is not required to attend.</p>
      </div>
      <a class="btn btn-ghost" href="<?= url('events') ?>">All events <?= icon('arrow') ?></a>
    </div>

    <div class="grid grid-3">
      <?php foreach ($events as $ev) eventCard($ev); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ======================================================== competitions -->
<?php if ($competitions): ?>
<section class="section">
  <div class="container">
    <div class="section-head reveal" style="display:flex;flex-wrap:wrap;align-items:end;justify-content:space-between;gap:1.5rem;max-width:none">
      <div style="max-width:40rem">
        <span class="eyebrow">Compete</span>
        <h2>Contests we host and win</h2>
        <p>From the campus championship to a 118-team national IUPC.</p>
      </div>
      <a class="btn btn-ghost" href="<?= url('competitions') ?>">All competitions <?= icon('arrow') ?></a>
    </div>

    <div class="grid grid-3">
      <?php foreach ($competitions as $c) competitionCard($c); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================================================ projects -->
<?php if ($projects): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal" style="display:flex;flex-wrap:wrap;align-items:end;justify-content:space-between;gap:1.5rem;max-width:none">
      <div style="max-width:40rem">
        <span class="eyebrow">Built here</span>
        <h2>Projects running on campus</h2>
        <p>Not coursework. Software our members built that other people now depend on.</p>
      </div>
      <a class="btn btn-ghost" href="<?= url('projects') ?>">All projects <?= icon('arrow') ?></a>
    </div>

    <div class="grid grid-3">
      <?php foreach ($projects as $p) projectCard($p); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========================================================== membership -->
<section class="section">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Membership</span>
      <h2>Join in under five minutes</h2>
      <p>Apply online, pay with bKash, Nagad or Rocket, and get approved once the Treasurer confirms your transaction.</p>
    </div>

    <div class="plans">
      <?php foreach ($plans as $plan):
          $benefits = array_filter(array_map('trim', explode("\n", (string) $plan['benefits']))); ?>
        <div class="plan reveal <?= $plan['is_featured'] ? 'is-featured' : '' ?>">
          <h3><?= e($plan['name']) ?></h3>
          <p class="plan-tagline"><?= e($plan['tagline']) ?></p>
          <div class="plan-price">
            <span class="amount"><?= e(taka($plan['fee'])) ?></span>
            <span class="per"><?= $plan['duration_months'] > 0 ? '/ year' : 'one-time' ?></span>
          </div>
          <ul class="plan-benefits">
            <?php foreach (array_slice($benefits, 0, 5) as $b): ?>
              <li><?= icon('check') ?><span><?= e($b) ?></span></li>
            <?php endforeach; ?>
          </ul>
          <a class="btn <?= $plan['is_featured'] ? '' : 'btn-ghost' ?> btn-block" href="<?= url('membership') ?>">Choose <?= e($plan['name']) ?></a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="reveal" style="margin-top:2.5rem;display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:1rem 2rem">
      <span class="muted">Pay securely from your own wallet:</span>
      <span class="badge" style="--wallet:var(--bkash);border-color:var(--bkash);color:var(--bkash)"><?= icon('wallet') ?> bKash</span>
      <span class="badge" style="border-color:var(--nagad);color:var(--nagad)"><?= icon('wallet') ?> Nagad</span>
      <span class="badge" style="border-color:var(--rocket);color:var(--rocket)"><?= icon('wallet') ?> Rocket</span>
    </div>
  </div>
</section>

<!-- =========================================================== committee -->
<?php if ($committee): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Who runs it</span>
      <h2>The Executive Committee</h2>
      <p>Elected by members every academic year, and entirely student-run.</p>
    </div>

    <div class="grid grid-4">
      <?php foreach ($committee as $m) personCard($m); ?>
    </div>

    <div class="center reveal" style="margin-top:2.5rem">
      <a class="btn btn-ghost" href="<?= url('committee') ?>">Meet the full committee <?= icon('arrow') ?></a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ================================================================ blog -->
<?php if ($posts): ?>
<section class="section">
  <div class="container">
    <div class="section-head reveal" style="display:flex;flex-wrap:wrap;align-items:end;justify-content:space-between;gap:1.5rem;max-width:none">
      <div style="max-width:40rem">
        <span class="eyebrow">From the blog</span>
        <h2>Written by members</h2>
        <p>Guides, retrospectives and advice — including the parts that did not work.</p>
      </div>
      <a class="btn btn-ghost" href="<?= url('blog') ?>">All articles <?= icon('arrow') ?></a>
    </div>

    <div class="grid grid-3">
      <?php foreach ($posts as $p) postCard($p); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ================================================================= CTA -->
<section class="section-tight">
  <div class="container">
    <div class="cta reveal">
      <h2>Your first contest is closer than you think</h2>
      <p>Most of our strongest members joined knowing nothing but a polytechnic C course. Start where they started.</p>
      <div class="btn-group" style="justify-content:center">
        <a class="btn btn-lg" href="<?= url('membership/apply') ?>"><?= icon('sparkle') ?> Apply for Membership</a>
        <a class="btn btn-outline btn-lg" href="<?= url('resources') ?>"><?= icon('book') ?> Browse Resources</a>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.hero-slide');

    if (slides.length <= 1) return;

    let currentSlide = 0;
    let sliderTimer;

    // Preload all hero images for smooth transitions.
    slides.forEach(function (slide) {
        if (slide.src) {
            const img = new Image();
            img.src = slide.src;
        }
    });

    function showNextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    function startSlider() {
        clearInterval(sliderTimer);
        sliderTimer = setInterval(showNextSlide, 2000);
    }

    startSlider();

    // Pause while the browser tab is hidden.
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            clearInterval(sliderTimer);
        } else {
            startSlider();
        }
    });
});
</script>
