<?php
$c = $competition;
pageHero($c['title'], $c['summary'] ?? '', ['Competitions' => 'competitions', $typeLabel => 'competitions/' . $c['type'], $c['title'] => null]);
$banner = upload($c['banner'], 'events');
?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <article>
        <div class="reveal" style="border-radius:var(--radius-xl);overflow:hidden;aspect-ratio:16/7;margin-bottom:2rem;box-shadow:var(--shadow-lg)">
          <?php if ($banner !== ''): ?>
            <img src="<?= e($banner) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
          <?php else: ?>
            <div style="width:100%;height:100%;display:grid;place-items:center;font-family:var(--font-display);font-size:clamp(2rem,7vw,4rem);font-weight:800;color:rgba(255,255,255,.9);<?= e(gradientFor($c['title'])) ?>"><?= e(initials($c['title'])) ?></div>
          <?php endif; ?>
        </div>

        <div class="meta-bar reveal">
          <?php if ($c['event_date']): ?><div><?= icon('calendar') ?><span><small>Date</small><b><?= e(fdate($c['event_date'])) ?></b></span></div><?php endif; ?>
          <?php if ($c['venue']): ?><div><?= icon('pin') ?><span><small>Venue</small><b><?= e($c['venue']) ?></b></span></div><?php endif; ?>
          <?php if ($c['teams']): ?><div><?= icon('users') ?><span><small>Teams</small><b><?= (int) $c['teams'] ?></b></span></div><?php endif; ?>
          <?php if ($c['participants']): ?><div><?= icon('users') ?><span><small>Participants</small><b><?= (int) $c['participants'] ?></b></span></div><?php endif; ?>
          <?php if ($c['prize_pool']): ?><div><?= icon('award') ?><span><small>Prize pool</small><b><?= e($c['prize_pool']) ?></b></span></div><?php endif; ?>
        </div>

        <?php if ($c['champion']): ?>
          <div class="reveal" style="display:flex;align-items:center;gap:1rem;padding:1.25rem 1.5rem;border-radius:var(--radius-lg);background:linear-gradient(135deg,rgba(245,158,11,.12),rgba(245,158,11,.04));border:1px solid rgba(245,158,11,.3);margin-bottom:2rem">
            <span style="display:grid;place-items:center;width:48px;height:48px;border-radius:50%;background:var(--amber-500);color:#fff;flex:none"><?= icon('trophy') ?></span>
            <div>
              <small class="muted" style="display:block;font-size:var(--step--1)">Champion</small>
              <b style="font-size:var(--step-1)"><?= e($c['champion']) ?></b>
            </div>
          </div>
        <?php endif; ?>

        <div class="prose reveal"><?= $c['content'] ?></div>

        <div class="btn-group reveal" style="margin-top:2.5rem">
          <?php if ($c['register_url'] && $c['status'] !== 'past'): ?>
            <a class="btn btn-lg" href="<?= e($c['register_url']) ?>" target="_blank" rel="noopener"><?= icon('external') ?> Register now</a>
          <?php endif; ?>
          <?php if ($c['result_url']): ?>
            <a class="btn btn-ghost btn-lg" href="<?= e($c['result_url']) ?>" target="_blank" rel="noopener"><?= icon('award') ?> Full results</a>
          <?php endif; ?>
        </div>
      </article>

      <aside class="sidebar">
        <div class="widget">
          <h3>Status</h3>
          <p><span class="badge <?= e(statusBadge($c['status'])) ?>" style="font-size:var(--step-0)"><?= e(statusLabel($c['status'])) ?></span>
             <span class="badge badge-violet"><?= e($typeLabel) ?></span></p>
        </div>

        <?php if ($related): ?>
          <div class="widget">
            <h3>Other <?= e($typeLabel) ?> editions</h3>
            <ul class="widget-list">
              <?php foreach ($related as $r): ?>
                <li><a href="<?= url('competition/' . $r['slug']) ?>"><?= e($r['title']) ?></a><small><?= e(fdate($r['event_date'])) ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="widget">
          <h3>Preparing to compete?</h3>
          <p class="soft" style="margin-bottom:1rem;font-size:var(--step-0)">Our programming wing runs a year-round camp and a weekly rated contest.</p>
          <a class="btn btn-block" href="<?= url('activities/programming') ?>"><?= icon('code') ?> Programming Wing</a>
        </div>
      </aside>
    </div>
  </div>
</section>
