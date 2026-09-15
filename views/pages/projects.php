<?php pageHero($title, $subtitle, ['Projects' => 'projects']);
$labels = ['web' => 'Web', 'mobile' => 'Mobile', 'ai' => 'AI / ML', 'iot' => 'IoT', 'security' => 'Security', 'other' => 'Other'];
$base = url('projects') . (PRETTY_URLS ? '?' : '&');
?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <a class="filter-chip <?= $cat === 'all' ? 'is-active' : '' ?>" href="<?= url('projects') ?>">
        <?= icon('layers') ?> All <span class="count"><?= array_sum($cats) ?></span>
      </a>
      <?php foreach ($cats as $key => $n): ?>
        <a class="filter-chip <?= $cat === $key ? 'is-active' : '' ?>" href="<?= e($base . 'category=' . urlencode($key)) ?>">
          <?= e($labels[$key] ?? ucfirst($key)) ?> <span class="count"><?= $n ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (!$projects): ?>
      <?php emptyState('No projects in this category', 'Try another category, or submit yours to the committee.', 'layers'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($projects as $p) projectCard($p); ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="cta reveal">
      <h2>Built something? Show it here.</h2>
      <p>Any DUET student can submit a project for the showcase. Working software beats a polished slide deck every time.</p>
      <a class="btn btn-lg" href="<?= url('contact') ?>"><?= icon('mail') ?> Submit a Project</a>
    </div>
  </div>
</section>
