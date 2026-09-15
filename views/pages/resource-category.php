<?php
pageHero($category['name'] . ' Resources', $category['description'] ?? '', ['Resources' => 'resources', $category['name'] => null]);
$typeIcons = ['book' => 'book', 'course' => 'play', 'video' => 'play', 'tool' => 'settings',
              'roadmap' => 'target', 'article' => 'file', 'practice' => 'code'];
$base = url('resources/' . $category['slug']) . (PRETTY_URLS ? '?' : '&');
?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <?php foreach ($categories as $c): ?>
        <a class="filter-chip <?= $c['slug'] === $category['slug'] ? 'is-active' : '' ?>" href="<?= url('resources/' . $c['slug']) ?>">
          <?= icon($c['icon']) ?> <?= e($c['name']) ?> <span class="count"><?= (int) ($counts[$c['slug']] ?? 0) ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="filter-row reveal">
      <a class="filter-chip <?= $level === 'all' ? 'is-active' : '' ?>" href="<?= url('resources/' . $category['slug']) ?>">All levels</a>
      <?php foreach (['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'] as $k => $lbl): ?>
        <a class="filter-chip <?= $level === $k ? 'is-active' : '' ?>" href="<?= e($base . 'level=' . $k) ?>"><?= e($lbl) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (!$resources): ?>
      <?php emptyState('Nothing matches that filter', 'Try a different level, or another category.', 'search'); ?>
    <?php else: ?>
      <div class="grid grid-2">
        <?php foreach ($resources as $r): ?>
          <a class="card reveal" href="<?= e($r['url']) ?>" target="_blank" rel="noopener">
            <div class="card-body">
              <div class="card-meta">
                <span class="badge badge-brand"><?= icon($typeIcons[$r['type']] ?? 'book') ?> <?= e(ucfirst($r['type'])) ?></span>
                <span class="badge"><?= e(ucfirst($r['level'])) ?></span>
                <?php if ($r['is_free']): ?><span class="badge badge-success">Free</span><?php endif; ?>
              </div>
              <h3 class="card-title" style="display:flex;align-items:start;gap:.5rem">
                <span><?= e($r['title']) ?></span>
                <?= icon('external', 'icon') ?>
              </h3>
              <p class="card-text"><?= e($r['description']) ?></p>
            </div>
            <?php if ($r['provider']): ?>
              <div class="card-foot"><span><?= icon('users') ?> <?= e($r['provider']) ?></span><span class="link">Open <?= icon('arrow') ?></span></div>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
