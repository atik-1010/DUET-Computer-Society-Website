<?php pageHero($title, $subtitle, ['Resources' => 'resources']); ?>

<section class="section">
  <div class="container">
    <div class="stats-strip reveal" style="margin-bottom:3rem">
      <div class="stat"><span class="num" data-count="<?= (int) $total ?>">0</span><span class="lbl">Curated links</span></div>
      <div class="stat"><span class="num"><?= count($categories) ?></span><span class="lbl">Categories</span></div>
      <div class="stat"><span class="num">100%</span><span class="lbl">Free to access</span></div>
      <div class="stat"><span class="num">0</span><span class="lbl">Sign-ups required</span></div>
    </div>

    <div class="grid grid-2">
      <?php foreach ($categories as $c): ?>
        <a class="feature reveal" href="<?= url('resources/' . $c['slug']) ?>"
           style="--accent:linear-gradient(135deg, <?= e($c['accent']) ?>, <?= e($c['accent']) ?>bb); --accent-solid:<?= e($c['accent']) ?>">
          <div style="display:flex;align-items:center;gap:1rem">
            <span class="feature-icon"><?= icon($c['icon']) ?></span>
            <div>
              <h3><?= e($c['name']) ?></h3>
              <span class="muted" style="font-size:var(--step--1)"><?= (int) ($counts[$c['slug']] ?? 0) ?> resources</span>
            </div>
          </div>
          <p><?= e($c['description']) ?></p>
          <span class="link">Browse <?= icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
