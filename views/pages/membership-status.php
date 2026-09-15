<?php pageHero($title, $subtitle, ['Membership' => 'membership', 'Status' => null]);
$latest = $payments[0] ?? null;
?>

<section class="section">
  <div class="container container-narrow">

    <?php if ($member['status'] === 'approved'): ?>
      <div class="cert-result reveal" style="margin-bottom:2rem">
        <div class="cert-seal"><?= icon('check') ?></div>
        <h2 style="font-size:var(--step-2);margin-bottom:.5rem">Membership approved</h2>
        <p class="soft">
          Welcome to DUETCS. Your member number is <b class="cert-no"><?= e($member['member_no']) ?></b>
          <?php if ($member['expires_on']): ?> and it is valid until <b><?= e(fdate($member['expires_on'])) ?></b><?php else: ?> — this is a lifetime membership<?php endif; ?>.
        </p>
      </div>
    <?php elseif ($member['status'] === 'pending'): ?>
      <div class="alert alert-warning reveal">
        <?= icon('clock') ?>
        <div><strong>Awaiting verification.</strong> The Treasurer is checking your transaction against the wallet statement. This usually takes under 24 hours on working days.</div>
      </div>
    <?php elseif ($member['status'] === 'awaiting_payment'): ?>
      <div class="alert alert-info reveal">
        <?= icon('wallet') ?>
        <div>
          <strong>One step left.</strong> Your application is saved but no payment has been recorded yet.
          <a class="link" href="<?= url('membership/payment') ?>">Submit your payment <?= icon('arrow') ?></a>
        </div>
      </div>
    <?php elseif ($member['status'] === 'rejected'): ?>
      <div class="alert alert-error reveal">
        <?= icon('close') ?>
        <div>
          <strong>Application not approved.</strong>
          <?= $member['admin_note'] ? e($member['admin_note']) : 'Contact the committee for details.' ?>
        </div>
      </div>
    <?php elseif ($member['status'] === 'expired'): ?>
      <div class="alert alert-warning reveal">
        <?= icon('clock') ?>
        <div><strong>Membership expired.</strong> Submit a new payment to renew for another term.</div>
      </div>
    <?php endif; ?>

    <!-- application summary -->
    <div class="receipt reveal" style="margin-bottom:1.5rem">
      <div class="receipt-head">
        <div>
          <h3 style="font-size:var(--step-1)">Application</h3>
          <small class="muted">Submitted <?= e(fdate($member['applied_at'], 'd M Y, g:i A')) ?></small>
        </div>
        <span class="badge <?= e(statusBadge($member['status'])) ?>" style="font-size:var(--step-0)"><?= e(statusLabel($member['status'])) ?></span>
      </div>
      <dl class="receipt-body">
        <div class="receipt-row"><dt>Name</dt><dd><?= e($member['full_name']) ?></dd></div>
        <?php if ($member['member_no']): ?><div class="receipt-row"><dt>Member number</dt><dd class="mono"><?= e($member['member_no']) ?></dd></div><?php endif; ?>
        <div class="receipt-row"><dt>Student ID</dt><dd><?= e($member['student_id']) ?></dd></div>
        <div class="receipt-row"><dt>Department</dt><dd><?= e($member['department']) ?> · <?= e($member['series']) ?></dd></div>
        <div class="receipt-row"><dt>Email</dt><dd><?= e($member['email']) ?></dd></div>
        <div class="receipt-row"><dt>Mobile</dt><dd><?= e($member['phone']) ?></dd></div>
        <?php if ($plan): ?><div class="receipt-row"><dt>Plan</dt><dd><?= e($plan['name']) ?></dd></div><?php endif; ?>
        <?php if ($member['expires_on']): ?><div class="receipt-row"><dt>Valid until</dt><dd><?= e(fdate($member['expires_on'])) ?></dd></div><?php endif; ?>
        <?php if ($member['admin_note']): ?><div class="receipt-row"><dt>Note from the committee</dt><dd><?= e($member['admin_note']) ?></dd></div><?php endif; ?>
      </dl>
    </div>

    <!-- payments -->
    <h3 style="font-size:var(--step-2);margin:2.5rem 0 1.2rem">Payment history</h3>

    <?php if (!$payments): ?>
      <?php emptyState('No payment recorded yet', 'Submit your membership payment to complete the application.', 'wallet'); ?>
      <div class="center" style="margin-top:1.5rem">
        <a class="btn" href="<?= url('membership/payment') ?>"><?= icon('wallet') ?> Submit payment</a>
      </div>
    <?php else: ?>
      <?php foreach ($payments as $pay): ?>
        <div class="receipt reveal" style="margin-bottom:1.2rem">
          <div class="receipt-head">
            <div>
              <span class="badge" style="border-color:var(--<?= e($pay['method']) ?>);color:var(--<?= e($pay['method']) ?>)">
                <?= icon('wallet') ?> <?= e(ucfirst($pay['method'])) ?>
              </span>
              <div style="margin-top:.4rem"><small class="muted mono"><?= e($pay['reference']) ?></small></div>
            </div>
            <span class="badge <?= e(statusBadge($pay['status'])) ?>" style="font-size:var(--step-0)"><?= e(statusLabel($pay['status'])) ?></span>
          </div>
          <dl class="receipt-body">
            <div class="receipt-row"><dt>Transaction ID</dt><dd class="mono"><?= e($pay['trx_id']) ?></dd></div>
            <div class="receipt-row"><dt>Paid from</dt><dd><?= e($pay['sender_number']) ?></dd></div>
            <div class="receipt-row"><dt>Paid to</dt><dd><?= e($pay['receiver_number']) ?></dd></div>
            <div class="receipt-row"><dt>Submitted</dt><dd><?= e(fdate($pay['submitted_at'], 'd M Y, g:i A')) ?></dd></div>
            <?php if ($pay['verified_at']): ?><div class="receipt-row"><dt>Reviewed</dt><dd><?= e(fdate($pay['verified_at'], 'd M Y, g:i A')) ?></dd></div><?php endif; ?>
            <?php if ($pay['admin_note']): ?><div class="receipt-row"><dt>Note</dt><dd><?= e($pay['admin_note']) ?></dd></div><?php endif; ?>
          </dl>
          <div class="receipt-total"><span>Amount</span><span class="amount"><?= e(taka($pay['amount'])) ?></span></div>
        </div>
      <?php endforeach; ?>

      <?php if ($latest && $latest['status'] === 'rejected'): ?>
        <div class="center" style="margin-top:1.5rem">
          <a class="btn" href="<?= url('membership/payment') ?>"><?= icon('wallet') ?> Submit a corrected payment</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <div class="btn-group" style="margin-top:2.5rem">
      <a class="btn btn-ghost" href="<?= url('dashboard') ?>"><?= icon('dashboard') ?> Your dashboard</a>
      <a class="btn btn-ghost" href="<?= url('contact') ?>"><?= icon('mail') ?> Contact the Treasurer</a>
    </div>
  </div>
</section>
