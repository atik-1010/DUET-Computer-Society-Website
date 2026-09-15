<div class="admin-stats" style="grid-template-columns:repeat(auto-fit,minmax(min(100%,15rem),1fr))">
  <div class="admin-stat" style="--tile:linear-gradient(135deg,#10b981,#059669)">
    <span class="admin-stat-icon"><?= icon('check') ?></span>
    <span><span class="v"><?= e(taka($totals['verified'])) ?></span><span class="k">Verified income</span></span>
  </div>
  <div class="admin-stat" style="--tile:linear-gradient(135deg,#f59e0b,#d97706)">
    <span class="admin-stat-icon"><?= icon('clock') ?></span>
    <span><span class="v"><?= e(taka($totals['pending'])) ?></span><span class="k">Awaiting verification</span></span>
  </div>
  <div class="admin-stat" style="--tile:linear-gradient(135deg,#6366f1,#06b6d4)">
    <span class="admin-stat-icon"><?= icon('wallet') ?></span>
    <span><span class="v"><?= number_format($counts['all'] ?? 0) ?></span><span class="k">Total submissions</span></span>
  </div>
</div>

<div class="admin-toolbar">
  <?php foreach (['pending' => 'Pending', 'verified' => 'Verified', 'rejected' => 'Rejected', 'all' => 'All'] as $k => $lbl): ?>
    <a class="filter-chip <?= $status === $k ? 'is-active' : '' ?>"
       href="<?= url('admin/payments') . (PRETTY_URLS ? '?' : '&') . 'status=' . $k ?>">
      <?= e($lbl) ?> <span class="count"><?= (int) ($counts[$k] ?? 0) ?></span>
    </a>
  <?php endforeach; ?>

  <form method="get" style="margin-left:auto">
    <?php if (!PRETTY_URLS): ?><input type="hidden" name="p" value="admin/payments"><?php endif; ?>
    <input type="hidden" name="status" value="<?= e($status) ?>">
    <div class="input-group">
      <?= icon('search') ?>
      <input class="input" type="search" name="q" value="<?= e($search) ?>" placeholder="TrxID, name or number…">
    </div>
    <button class="btn" type="submit">Search</button>
  </form>
</div>

<?php if (!$payments): ?>
  <?php emptyState('Nothing in this queue', 'Try another status filter, or clear the search.', 'wallet'); ?>
<?php else: ?>
  <?php foreach ($payments as $p):
      $shot = upload($p['screenshot'], 'payments'); ?>
    <div class="review-card">
      <div>
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.6rem;margin-bottom:.3rem">
          <span class="wallet-tag <?= e($p['method']) ?>"><?= icon('wallet') ?> <?= e(ucfirst($p['method'])) ?></span>
          <span class="badge <?= e(statusBadge($p['status'])) ?>"><?= e(statusLabel($p['status'])) ?></span>
          <span class="badge mono"><?= e($p['reference']) ?></span>
        </div>

        <h3 style="font-size:var(--step-1)"><?= e($p['full_name']) ?></h3>
        <p class="muted" style="font-size:var(--step--1)">
          <?= e($p['student_id']) ?> · <?= e($p['member_email']) ?>
        </p>

        <dl class="review-grid">
          <div><dt>Transaction ID</dt><dd class="mono"><?= e($p['trx_id']) ?></dd></div>
          <div><dt>Amount</dt><dd><?= e(taka($p['amount'])) ?></dd></div>
          <div><dt>Plan</dt><dd><?= e($p['plan_name'] ?: '—') ?></dd></div>
          <div><dt>Paid from</dt><dd class="mono"><?= e($p['sender_number']) ?></dd></div>
          <div><dt>Paid to</dt><dd class="mono"><?= e($p['receiver_number']) ?></dd></div>
          <div><dt>Submitted</dt><dd><?= e(fdate($p['submitted_at'], 'd M Y, g:i A')) ?></dd></div>
          <?php if ($p['verified_at']): ?>
            <div><dt>Reviewed</dt><dd><?= e(fdate($p['verified_at'], 'd M Y, g:i A')) ?></dd></div>
          <?php endif; ?>
        </dl>

        <?php if ($p['admin_note']): ?>
          <p class="muted" style="margin-top:.9rem;font-size:var(--step--1)"><b>Note:</b> <?= e($p['admin_note']) ?></p>
        <?php endif; ?>

        <div class="alert alert-info" style="margin-top:1rem;font-size:var(--step--1)">
          <?= icon('shield') ?>
          <span>Check this transaction ID against the <?= e(ucfirst($p['method'])) ?> statement for
            <?= e($p['receiver_number']) ?> before verifying. Verifying approves the membership automatically.</span>
        </div>
      </div>

      <div class="review-side">
        <?php if ($shot !== ''): ?>
          <figure class="proof gallery-item" style="cursor:zoom-in">
            <img src="<?= e($shot) ?>" alt="Payment screenshot" loading="lazy">
            <figcaption>Screenshot · <?= e($p['trx_id']) ?></figcaption>
          </figure>
        <?php else: ?>
          <div class="proof" style="display:grid;place-items:center;color:var(--text-mute);font-size:var(--step--1);text-align:center;padding:1rem">
            <span><?= icon('image') ?><br>No screenshot<br>submitted</span>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= url('admin/payments/update/' . $p['id']) ?>" data-confirm="Confirm this decision?">
          <?= csrfField() ?>
          <div class="field" style="margin-bottom:.6rem">
            <label class="sr-only" for="note-<?= (int) $p['id'] ?>">Note</label>
            <input class="input" type="text" id="note-<?= (int) $p['id'] ?>" name="admin_note"
                   placeholder="Note (optional)" value="<?= e($p['admin_note']) ?>" maxlength="255">
          </div>
          <div class="btn-group" style="gap:.5rem">
            <?php if ($p['status'] !== 'verified'): ?>
              <button class="btn btn-success btn-sm" type="submit" name="status" value="verified"><?= icon('check') ?> Verify</button>
            <?php endif; ?>
            <?php if ($p['status'] !== 'rejected'): ?>
              <button class="btn btn-danger btn-sm" type="submit" name="status" value="rejected"><?= icon('close') ?> Reject</button>
            <?php endif; ?>
            <?php if ($p['status'] !== 'pending'): ?>
              <button class="btn btn-ghost btn-sm" type="submit" name="status" value="pending"><?= icon('clock') ?> Reopen</button>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
