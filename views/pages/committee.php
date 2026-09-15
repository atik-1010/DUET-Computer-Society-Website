<?php
$crumbs = ['Executive Committee' => 'committee'];
if ($view !== 'current') {
    $crumbs[$title] = null;
}
pageHero($title, $subtitle, $crumbs);
?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <a class="filter-chip <?= $view === 'current' ? 'is-active' : '' ?>" href="<?= url('committee') ?>"><?= icon('users') ?> Current Committee</a>
      <a class="filter-chip <?= $view === 'previous' ? 'is-active' : '' ?>" href="<?= url('committee/previous') ?>"><?= icon('clock') ?> Previous Committees</a>
      <a class="filter-chip <?= $view === 'advisors' ? 'is-active' : '' ?>" href="<?= url('committee/advisors') ?>"><?= icon('award') ?> Advisors</a>
    </div>

    <?php if (!$groups): ?>
      <?php emptyState('No committee recorded yet', 'Add a committee and its members from the admin panel.', 'users'); ?>
    <?php endif; ?>

    <?php foreach ($groups as $i => $g): ?>
      <div style="margin-top:<?= $i ? '3.5rem' : '0' ?>">
        <div class="section-head reveal" style="margin-bottom:2rem">
          <h2 style="font-size:var(--step-3)">
            <?= e($g['committee']['title']) ?>
            <span class="badge badge-brand" style="vertical-align:middle;margin-left:.5rem"><?= e($g['committee']['term']) ?></span>
            <?php if ($g['committee']['is_current']): ?>
              <span class="badge badge-success badge-dot" style="vertical-align:middle">Serving now</span>
            <?php endif; ?>
          </h2>
          <?php if (!empty($g['committee']['description'])): ?>
            <p><?= e($g['committee']['description']) ?></p>
          <?php endif; ?>
        </div>

        <?php if ($g['members']): ?>
          <div class="grid grid-4">
            <?php foreach ($g['members'] as $m) personCard($m); ?>
          </div>
        <?php else: ?>
          <?php emptyState('No members listed', 'This committee has no members recorded yet.', 'users'); ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="cta reveal">
      <h2>Want a seat on the committee?</h2>
      <p>Executive positions are elected annually from members in good standing. Executive membership makes you eligible to stand.</p>
      <a class="btn btn-lg" href="<?= url('membership') ?>"><?= icon('sparkle') ?> View Membership Plans</a>
    </div>
  </div>
</section>
