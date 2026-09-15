<?php
pageHero($category['name'], $category['tagline'] ?? '', ['Activities' => 'activities', $category['name'] => null]);
?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <?php foreach ($categories as $c): ?>
        <a class="filter-chip <?= $c['slug'] === $category['slug'] ? 'is-active' : '' ?>" href="<?= url('activities/' . $c['slug']) ?>">
          <?= icon($c['icon']) ?> <?= e($c['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($category['description'])): ?>
      <div class="reveal" style="padding:1.5rem 1.75rem;border-radius:var(--radius-lg);background:var(--surface);border:1px solid var(--border);border-left:4px solid <?= e($category['accent']) ?>;margin-bottom:2.5rem">
        <p class="soft" style="font-size:var(--step-1)"><?= $category['description'] ?></p>
      </div>
    <?php endif; ?>

    <?php if (!$activities): ?>
      <?php emptyState('Nothing scheduled in this wing yet', 'The committee publishes each wing\'s programme at the start of the semester.', 'calendar'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($activities as $a) activityCard($a); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
