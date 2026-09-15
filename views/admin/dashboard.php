<div class="admin-stats">
  <?php
  $tiles = [
      ['users',    $counts['members'],  'Total applications', 'linear-gradient(135deg,#6366f1,#06b6d4)', 'members'],
      ['clock',    $counts['pendingM'], 'Awaiting approval',  'linear-gradient(135deg,#f59e0b,#ef4444)', 'members'],
      ['wallet',   $counts['payments'], 'Payments to verify', 'linear-gradient(135deg,#e2136e,#8c3494)', 'payments'],
      ['award',    taka($counts['revenue']), 'Verified income', 'linear-gradient(135deg,#10b981,#059669)', 'payments'],
      ['calendar', $counts['events'],   'Events',             'linear-gradient(135deg,#8b5cf6,#6366f1)', 'events'],
      ['book',     $counts['posts'],    'Blog posts',         'linear-gradient(135deg,#06b6d4,#0891b2)', 'posts'],
      ['mail',     $counts['messages'], 'Unread messages',    'linear-gradient(135deg,#f43f5e,#be123c)', 'messages'],
      ['award',    $counts['certs'],    'Certificates',       'linear-gradient(135deg,#f59e0b,#d97706)', 'certificates'],
  ];
  foreach ($tiles as [$ic, $val, $label, $grad, $link]): ?>
    <a class="admin-stat" href="<?= url('admin/' . $link) ?>" style="--tile:<?= e($grad) ?>">
      <span class="admin-stat-icon"><?= icon($ic) ?></span>
      <span>
        <span class="v"><?= is_string($val) ? e($val) : number_format($val) ?></span>
        <span class="k"><?= e($label) ?></span>
      </span>
    </a>
  <?php endforeach; ?>
</div>

<div class="grid" style="--grid-gap:1.5rem;grid-template-columns:minmax(0,1.5fr) minmax(0,1fr);align-items:start">

  <div>
    <!-- payments needing action -->
    <div class="panel">
      <div class="panel-head">
        <h2><?= icon('wallet') ?> Payments awaiting verification</h2>
        <a class="btn btn-ghost btn-sm" href="<?= url('admin/payments') ?>">Open queue <?= icon('arrow') ?></a>
      </div>
      <?php if (!$payments): ?>
        <div class="panel-body"><p class="muted"><?= icon('check') ?> Nothing waiting. The queue is clear.</p></div>
      <?php else: ?>
        <div class="panel-body flush">
          <div class="table-wrap" style="border:0;border-radius:0">
            <table class="table">
              <thead><tr><th>Applicant</th><th>Wallet</th><th>TrxID</th><th>Amount</th><th>Submitted</th><th></th></tr></thead>
              <tbody>
                <?php foreach ($payments as $p): ?>
                  <tr>
                    <td>
                      <b><?= e($p['full_name']) ?></b>
                      <div class="muted" style="font-size:var(--step--1)"><?= e($p['student_id']) ?></div>
                    </td>
                    <td><span class="wallet-tag <?= e($p['method']) ?>"><?= e(ucfirst($p['method'])) ?></span></td>
                    <td class="mono" style="font-size:var(--step--1)"><?= e($p['trx_id']) ?></td>
                    <td><b><?= e(taka($p['amount'])) ?></b></td>
                    <td class="muted"><?= e(ago($p['submitted_at'])) ?></td>
                    <td class="row-actions">
                      <a class="btn btn-sm" href="<?= url('admin/payments') ?>">Review</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- latest applications -->
    <div class="panel">
      <div class="panel-head">
        <h2><?= icon('users') ?> Latest applications</h2>
        <a class="btn btn-ghost btn-sm" href="<?= url('admin/members') ?>">All members <?= icon('arrow') ?></a>
      </div>
      <?php if (!$members): ?>
        <div class="panel-body"><p class="muted">No applications yet.</p></div>
      <?php else: ?>
        <div class="panel-body flush">
          <div class="table-wrap" style="border:0;border-radius:0">
            <table class="table">
              <thead><tr><th>Name</th><th>Department</th><th>Status</th><th>Applied</th></tr></thead>
              <tbody>
                <?php foreach ($members as $m): ?>
                  <tr>
                    <td>
                      <b><?= e($m['full_name']) ?></b>
                      <div class="muted" style="font-size:var(--step--1)"><?= e($m['student_id']) ?></div>
                    </td>
                    <td><?= e($m['department']) ?> · <?= e($m['series']) ?></td>
                    <td><span class="badge <?= e(statusBadge($m['status'])) ?>"><?= e(statusLabel($m['status'])) ?></span></td>
                    <td class="muted"><?= e(ago($m['applied_at'])) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div>
    <!-- income by month -->
    <div class="panel">
      <div class="panel-head"><h2><?= icon('wallet') ?> Verified income</h2></div>
      <div class="panel-body">
        <?php if (!$monthly): ?>
          <p class="muted">No verified payments yet.</p>
        <?php else:
          $max = max(array_map(fn($r) => (float) $r['total'], $monthly)) ?: 1; ?>
          <div class="stack" style="--stack-gap:.9rem">
            <?php foreach (array_reverse($monthly) as $r): ?>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:var(--step--1);margin-bottom:.3rem">
                  <span class="muted"><?= e(date('M Y', strtotime($r['ym'] . '-01'))) ?></span>
                  <b><?= e(taka($r['total'])) ?> <span class="muted" style="font-weight:400">· <?= (int) $r['n'] ?></span></b>
                </div>
                <div style="height:8px;border-radius:4px;background:var(--surface-3);overflow:hidden">
                  <div style="height:100%;width:<?= round(((float) $r['total'] / $max) * 100) ?>%;background:var(--gradient-brand);border-radius:4px"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- messages -->
    <div class="panel">
      <div class="panel-head">
        <h2><?= icon('mail') ?> Recent messages</h2>
        <a class="btn btn-ghost btn-sm" href="<?= url('admin/messages') ?>">All <?= icon('arrow') ?></a>
      </div>
      <div class="panel-body">
        <?php if (!$messages): ?>
          <p class="muted">No messages yet.</p>
        <?php else: ?>
          <ul class="widget-list">
            <?php foreach ($messages as $msg): ?>
              <li style="<?= $messages[0] === $msg ? 'border:0;padding:0;margin:0' : '' ?>">
                <a href="<?= url('admin/messages') ?>">
                  <?= e($msg['subject']) ?>
                  <?php if (!$msg['is_read']): ?><span class="badge badge-danger" style="margin-left:.4rem">New</span><?php endif; ?>
                </a>
                <small><?= e($msg['name']) ?> · <?= e(ago($msg['created_at'])) ?></small>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <!-- quick add -->
    <div class="panel">
      <div class="panel-head"><h2><?= icon('plus') ?> Quick add</h2></div>
      <div class="panel-body stack" style="--stack-gap:.55rem">
        <a class="btn btn-ghost btn-block btn-sm" href="<?= url('admin/events/create') ?>"><?= icon('calendar') ?> New event</a>
        <a class="btn btn-ghost btn-block btn-sm" href="<?= url('admin/posts/create') ?>"><?= icon('book') ?> New blog post</a>
        <a class="btn btn-ghost btn-block btn-sm" href="<?= url('admin/certificates/create') ?>"><?= icon('award') ?> Issue certificate</a>
        <a class="btn btn-ghost btn-block btn-sm" href="<?= url('admin/gallery_photos/create') ?>"><?= icon('image') ?> Upload photo</a>
      </div>
    </div>
  </div>
</div>
