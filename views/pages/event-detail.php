<?php
$ev = $event;
pageHero($ev['title'], $ev['summary'] ?? '', ['Events' => 'events', $ev['title'] => null]);
$banner = upload($ev['banner'], 'events');
?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <article>
        <div class="reveal" style="border-radius:var(--radius-xl);overflow:hidden;aspect-ratio:16/7;margin-bottom:2rem;box-shadow:var(--shadow-lg)">
          <?php if ($banner !== ''): ?>
            <img src="<?= e($banner) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
          <?php else: ?>
            <div style="width:100%;height:100%;display:grid;place-items:center;font-family:var(--font-display);font-size:clamp(2rem,7vw,4rem);font-weight:800;color:rgba(255,255,255,.9);<?= e(gradientFor($ev['title'])) ?>">
              <?= e(initials($ev['title'])) ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="meta-bar reveal">
          <div><?= icon('calendar') ?><span><small>Starts</small><b><?= e(fdate($ev['start_datetime'], 'd M Y, g:i A')) ?></b></span></div>
          <?php if ($ev['end_datetime']): ?>
            <div><?= icon('clock') ?><span><small>Ends</small><b><?= e(fdate($ev['end_datetime'], 'd M Y, g:i A')) ?></b></span></div>
          <?php endif; ?>
          <?php if ($ev['venue']): ?>
            <div><?= icon('pin') ?><span><small>Venue</small><b><?= e($ev['venue']) ?></b></span></div>
          <?php endif; ?>
          <?php if ($ev['seats']): ?>
            <div><?= icon('users') ?><span><small>Seats</small><b><?= (int) $ev['seats'] ?></b></span></div>
          <?php endif; ?>
          <div><?= icon('wallet') ?><span><small>Fee</small><b><?= $ev['fee'] > 0 ? e(taka($ev['fee'])) : 'Free' ?></b></span></div>
        </div>

        <div class="prose reveal"><?= $ev['content'] ?></div>

        <?php if ($ev['registration_url'] && $ev['status'] !== 'past'): ?>
          <div class="reveal" style="margin-top:2.5rem">
            <a class="btn btn-lg" href="<?= e($ev['registration_url']) ?>" target="_blank" rel="noopener">
              <?= icon('external') ?> Register for this event
            </a>
          </div>
        <?php endif; ?>
      </article>

      <aside class="sidebar">
        <div class="widget">
          <h3>Event status</h3>
          <p><span class="badge <?= e(statusBadge($ev['status'])) ?>" style="font-size:var(--step-0)"><?= e(statusLabel($ev['status'])) ?></span></p>
          <?php if ($ev['category']): ?>
            <p style="margin-top:.8rem"><span class="badge"><?= e($ev['category']) ?></span></p>
          <?php endif; ?>
        </div>

        <?php if ($related): ?>
          <div class="widget">
            <h3><?= e(ucfirst(statusLabel($ev['status']))) ?> events</h3>
            <ul class="widget-list">
              <?php foreach ($related as $r): ?>
                <li>
                  <a href="<?= url('event/' . $r['slug']) ?>"><?= e($r['title']) ?></a>
                  <small><?= e(fdate($r['start_datetime'])) ?></small>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="widget">
          <h3>Not a member yet?</h3>
          <p class="soft" style="margin-bottom:1rem;font-size:var(--step-0)">Members get priority seats and discounted registration on paid events.</p>
          <a class="btn btn-block" href="<?= url('membership') ?>"><?= icon('sparkle') ?> Join DUETCS</a>
        </div>
      </aside>
    </div>
  </div>
</section>
