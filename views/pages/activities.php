<?php pageHero($title, $subtitle, ['Activities' => 'activities']); ?>

<section class="section">
  <div class="container">
    <div class="grid grid-2">
      <?php foreach ($categories as $c): ?>
        <a class="feature reveal" href="<?= url('activities/' . $c['slug']) ?>"
           style="--accent:linear-gradient(135deg, <?= e($c['accent']) ?>, <?= e($c['accent']) ?>bb); --accent-solid:<?= e($c['accent']) ?>">
          <div style="display:flex;align-items:center;gap:1rem">
            <span class="feature-icon"><?= icon($c['icon']) ?></span>
            <div>
              <h3><?= e($c['name']) ?></h3>
              <span class="muted" style="font-size:var(--step--1)"><?= (int) ($counts[$c['slug']] ?? 0) ?> programmes</span>
            </div>
          </div>
          <p><?= e($c['description'] ? strip_tags(html_entity_decode($c['description'])) : $c['tagline']) ?></p>
          <span class="link">See programmes <?= icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($featured): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Start here</span>
      <h2>Most popular programmes</h2>
      <p>These fill up fastest. All of them assume you are starting from zero.</p>
    </div>
    <div class="grid grid-3">
      <?php foreach ($featured as $a) activityCard($a); ?>
    </div>
  </div>
</section>
<?php endif; ?>
