<?php
$a = $activity;
$crumbs = ['Activities' => 'activities'];
if ($category) {
    $crumbs[$category['name']] = 'activities/' . $category['slug'];
}
$crumbs[$a['title']] = null;
pageHero($a['title'], $a['summary'] ?? '', $crumbs);
$img = upload($a['image'], 'events');
?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <article>
        <div class="reveal" style="border-radius:var(--radius-xl);overflow:hidden;aspect-ratio:16/7;margin-bottom:2rem;box-shadow:var(--shadow-lg)">
          <?php if ($img !== ''): ?>
            <img src="<?= e($img) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
          <?php else: ?>
            <div style="width:100%;height:100%;display:grid;place-items:center;font-family:var(--font-display);font-size:clamp(2rem,7vw,4rem);font-weight:800;color:rgba(255,255,255,.9);<?= e(gradientFor($a['title'])) ?>"><?= e(initials($a['title'])) ?></div>
          <?php endif; ?>
        </div>

        <div class="meta-bar reveal">
          <div><?= icon('target') ?><span><small>Level</small><b><?= e(ucfirst($a['level'])) ?></b></span></div>
          <?php if ($a['duration']): ?><div><?= icon('clock') ?><span><small>Duration</small><b><?= e($a['duration']) ?></b></span></div><?php endif; ?>
          <?php if ($a['instructor']): ?><div><?= icon('users') ?><span><small>Led by</small><b><?= e($a['instructor']) ?></b></span></div><?php endif; ?>
          <?php if ($a['activity_date']): ?><div><?= icon('calendar') ?><span><small>Starts</small><b><?= e(fdate($a['activity_date'])) ?></b></span></div><?php endif; ?>
        </div>

        <div class="prose reveal"><?= $a['content'] ?></div>
      </article>

      <aside class="sidebar">
        <?php if ($category): ?>
          <div class="widget" style="border-left:4px solid <?= e($category['accent']) ?>">
            <h3><?= e($category['name']) ?></h3>
            <p class="soft" style="font-size:var(--step-0)"><?= e($category['tagline']) ?></p>
            <a class="link" style="margin-top:.8rem" href="<?= url('activities/' . $category['slug']) ?>">All <?= e($category['name']) ?> programmes <?= icon('arrow') ?></a>
          </div>
        <?php endif; ?>

        <?php if ($related): ?>
          <div class="widget">
            <h3>Related programmes</h3>
            <ul class="widget-list">
              <?php foreach ($related as $r): ?>
                <li><a href="<?= url('activity/' . $r['slug']) ?>"><?= e($r['title']) ?></a><small><?= e(ucfirst($r['level'])) ?><?= $r['duration'] ? ' · ' . e($r['duration']) : '' ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="widget">
          <h3>Attending</h3>
          <p class="soft" style="margin-bottom:1rem;font-size:var(--step-0)">Open to all DUET students. Members get priority when seats are limited.</p>
          <a class="btn btn-block" href="<?= url('membership') ?>"><?= icon('sparkle') ?> Become a Member</a>
          <a class="btn btn-ghost btn-block" style="margin-top:.6rem" href="<?= url('contact') ?>"><?= icon('mail') ?> Ask a Question</a>
        </div>
      </aside>
    </div>
  </div>
</section>
