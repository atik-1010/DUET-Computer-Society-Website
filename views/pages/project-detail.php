<?php
$pr = $project;
pageHero($pr['title'], $pr['summary'] ?? '', ['Projects' => 'projects', $pr['title'] => null]);
$img = upload($pr['image'], 'projects');
$stack = array_filter(array_map('trim', explode(',', (string) $pr['tech_stack'])));
$team  = array_filter(array_map('trim', explode(',', (string) $pr['team_members'])));
?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <article>
        <div class="reveal" style="border-radius:var(--radius-xl);overflow:hidden;aspect-ratio:16/9;margin-bottom:2rem;box-shadow:var(--shadow-lg)">
          <?php if ($img !== ''): ?>
            <img src="<?= e($img) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
          <?php else: ?>
            <div style="width:100%;height:100%;display:grid;place-items:center;font-family:var(--font-display);font-size:clamp(2rem,7vw,4rem);font-weight:800;color:rgba(255,255,255,.9);<?= e(gradientFor($pr['title'])) ?>"><?= e(initials($pr['title'])) ?></div>
          <?php endif; ?>
        </div>

        <div class="prose reveal"><?= $pr['content'] ?></div>

        <div class="btn-group reveal" style="margin-top:2.5rem">
          <?php if ($pr['github_url']): ?>
            <a class="btn" href="<?= e($pr['github_url']) ?>" target="_blank" rel="noopener"><?= icon('github') ?> View source</a>
          <?php endif; ?>
          <?php if ($pr['demo_url']): ?>
            <a class="btn btn-ghost" href="<?= e($pr['demo_url']) ?>" target="_blank" rel="noopener"><?= icon('external') ?> Live demo</a>
          <?php endif; ?>
        </div>
      </article>

      <aside class="sidebar">
        <div class="widget">
          <h3>At a glance</h3>
          <dl>
            <div class="receipt-row"><dt>Status</dt><dd><span class="badge <?= $pr['status'] === 'ongoing' ? 'badge-success' : 'badge-brand' ?>"><?= e(ucfirst($pr['status'])) ?></span></dd></div>
            <?php if ($pr['year']): ?><div class="receipt-row"><dt>Year</dt><dd><?= e($pr['year']) ?></dd></div><?php endif; ?>
            <?php if ($pr['category']): ?><div class="receipt-row"><dt>Category</dt><dd><?= e(ucfirst($pr['category'])) ?></dd></div><?php endif; ?>
          </dl>
        </div>

        <?php if ($stack): ?>
          <div class="widget">
            <h3>Built with</h3>
            <div class="tag-cloud">
              <?php foreach ($stack as $t): ?><span class="badge"><?= e($t) ?></span><?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($team): ?>
          <div class="widget">
            <h3>Team</h3>
            <ul class="widget-list">
              <?php foreach ($team as $t): ?>
                <li style="display:flex;align-items:center;gap:.7rem;border:0;padding:0;margin-top:.7rem">
                  <span style="display:grid;place-items:center;width:34px;height:34px;border-radius:50%;color:#fff;font-size:.75rem;font-weight:700;flex:none;<?= e(gradientFor($t)) ?>"><?= e(initials($t)) ?></span>
                  <span style="font-size:var(--step-0)"><?= e($t) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ($related): ?>
          <div class="widget">
            <h3>More projects</h3>
            <ul class="widget-list">
              <?php foreach ($related as $r): ?>
                <li><a href="<?= url('project/' . $r['slug']) ?>"><?= e($r['title']) ?></a><small><?= e($r['year']) ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>
