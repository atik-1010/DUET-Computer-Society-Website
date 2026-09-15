<?php
$crumbs = ['Competitions' => 'competitions'];
if ($type !== 'all') {
    $crumbs[$title] = null;
}
pageHero($title, $subtitle, $crumbs);
?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <a class="filter-chip <?= $type === 'all' ? 'is-active' : '' ?>" href="<?= url('competitions') ?>">
        <?= icon('trophy') ?> All <span class="count"><?= (int) ($counts['all'] ?? 0) ?></span>
      </a>
      <?php foreach ($types as $key => [$label, $_]): ?>
        <a class="filter-chip <?= $type === $key ? 'is-active' : '' ?>" href="<?= url('competitions/' . $key) ?>">
          <?= e($label) ?> <span class="count"><?= (int) ($counts[$key] ?? 0) ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (!$competitions): ?>
      <?php emptyState('No competitions in this category yet', 'Check the other categories, or watch the events calendar.', 'trophy'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($competitions as $c) competitionCard($c); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
