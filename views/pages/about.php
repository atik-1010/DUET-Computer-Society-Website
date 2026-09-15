<?php pageHero($page['title'], $page['subtitle'] ?? '', $crumbs); ?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <article class="prose reveal">
        <?= $page['content'] /* trusted admin-authored HTML */ ?>
      </article>

      <aside class="sidebar">
        <div class="widget">
          <h3>In this section</h3>
          <ul class="widget-list">
            <?php foreach ($nav as $key => [$slug, $label]): ?>
              <li style="border:0;padding:0;margin-top:<?= $key === '' ? '0' : '.6rem' ?>">
                <a href="<?= url('about' . ($key ? '/' . $key : '')) ?>"
                   style="<?= $active === $key ? 'color:var(--brand-500);font-weight:700' : '' ?>">
                  <?= e($label) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="widget">
          <h3>DUETCS in numbers</h3>
          <div class="grid" style="--grid-gap:1rem;grid-template-columns:1fr 1fr">
            <?php foreach ($stats as $label => $value): ?>
              <div>
                <div class="stat" style="padding:0;text-align:left;background:none">
                  <span class="num" style="font-size:1.7rem"><?= number_format($value) ?></span>
                  <span class="lbl"><?= e($label) ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="widget">
          <h3>Join the society</h3>
          <p class="soft" style="margin-bottom:1rem;font-size:var(--step-0)">
            Open to every DUET student. Apply online and pay with bKash, Nagad or Rocket.
          </p>
          <a class="btn btn-block" href="<?= url('membership') ?>"><?= icon('sparkle') ?> Membership</a>
        </div>

        <?php if (!empty($page['updated_at'])): ?>
          <p class="muted" style="font-size:var(--step--1)">Last updated <?= e(fdate($page['updated_at'])) ?></p>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>
