<?php pageHero('Hello, ' . explode(' ', $me['name'])[0], $subtitle, ['Dashboard' => null]); ?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <div>
        <!-- membership status -->
        <div class="card reveal" style="padding:1.75rem;margin-bottom:1.5rem">
          <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.2rem">
            <h2 style="font-size:var(--step-2)">Membership</h2>
            <?php if ($member): ?>
              <span class="badge <?= e(statusBadge($member['status'])) ?>" style="font-size:var(--step-0)"><?= e(statusLabel($member['status'])) ?></span>
            <?php else: ?>
              <span class="badge">Not applied</span>
            <?php endif; ?>
          </div>

          <?php if (!$member): ?>
            <p class="soft" style="margin-bottom:1.4rem">
              You have an account but no membership application yet. Membership unlocks priority event registration,
              the member resource library and eligibility for committee positions.
            </p>
            <a class="btn" href="<?= url('membership') ?>"><?= icon('sparkle') ?> View membership plans</a>
          <?php else: ?>
            <dl>
              <?php if ($member['member_no']): ?>
                <div class="receipt-row"><dt>Member number</dt><dd class="mono"><?= e($member['member_no']) ?></dd></div>
              <?php endif; ?>
              <?php if ($plan): ?><div class="receipt-row"><dt>Plan</dt><dd><?= e($plan['name']) ?> · <?= e(taka($plan['fee'])) ?></dd></div><?php endif; ?>
              <div class="receipt-row"><dt>Applied</dt><dd><?= e(fdate($member['applied_at'])) ?></dd></div>
              <?php if ($member['approved_at']): ?><div class="receipt-row"><dt>Approved</dt><dd><?= e(fdate($member['approved_at'])) ?></dd></div><?php endif; ?>
              <?php if ($member['expires_on']): ?><div class="receipt-row"><dt>Valid until</dt><dd><?= e(fdate($member['expires_on'])) ?></dd></div><?php endif; ?>
            </dl>

            <div class="btn-group" style="margin-top:1.4rem">
              <a class="btn btn-ghost" href="<?= url('membership/status') ?>"><?= icon('file') ?> Full application</a>
              <?php if ($member['status'] === 'awaiting_payment'): ?>
                <a class="btn" href="<?= url('membership/payment') ?>"><?= icon('wallet') ?> Submit payment</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- payments -->
        <?php if ($payments): ?>
          <div class="card reveal" style="padding:1.75rem;margin-bottom:1.5rem">
            <h2 style="font-size:var(--step-2);margin-bottom:1.2rem">Payments</h2>
            <div class="table-wrap" style="border:0">
              <table class="table" style="min-width:32rem">
                <thead><tr><th>Reference</th><th>Wallet</th><th>TrxID</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                  <?php foreach ($payments as $p): ?>
                    <tr>
                      <td class="mono" style="font-size:var(--step--1)"><?= e($p['reference']) ?></td>
                      <td><span class="badge" style="border-color:var(--<?= e($p['method']) ?>);color:var(--<?= e($p['method']) ?>)"><?= e(ucfirst($p['method'])) ?></span></td>
                      <td class="mono" style="font-size:var(--step--1)"><?= e($p['trx_id']) ?></td>
                      <td><b><?= e(taka($p['amount'])) ?></b></td>
                      <td><span class="badge <?= e(statusBadge($p['status'])) ?>"><?= e(statusLabel($p['status'])) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>

        <!-- certificates -->
        <div class="card reveal" style="padding:1.75rem">
          <h2 style="font-size:var(--step-2);margin-bottom:1.2rem">Your certificates</h2>
          <?php if (!$certs): ?>
            <p class="soft">No certificates issued to <b><?= e($me['email']) ?></b> yet. They appear here automatically once the committee issues one against this email address.</p>
          <?php else: ?>
            <div class="stack" style="--stack-gap:.8rem">
              <?php foreach ($certs as $c): ?>
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1rem;padding:1rem 1.2rem;border-radius:var(--radius);border:1px solid var(--border);background:var(--surface-2)">
                  <span style="display:grid;place-items:center;width:44px;height:44px;border-radius:12px;background:var(--gradient-brand);color:#fff;flex:none"><?= icon('award') ?></span>
                  <div style="flex:1;min-width:12rem">
                    <b><?= e($c['title']) ?></b>
                    <div class="muted" style="font-size:var(--step--1)">
                      <?= e(ucfirst($c['type'])) ?><?= $c['position'] ? ' · ' . e($c['position']) : '' ?> · <?= e(fdate($c['issued_on'])) ?>
                    </div>
                  </div>
                  <span class="badge mono"><?= e($c['verify_code']) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <aside class="sidebar">
        <div class="widget reveal">
          <h3>Your account</h3>
          <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:1.2rem">
            <?php $av = upload($me['avatar'], 'members'); ?>
            <?php if ($av !== ''): ?>
              <img src="<?= e($av) ?>" alt="" style="width:52px;height:52px;border-radius:50%;object-fit:cover">
            <?php else: ?>
              <span style="display:grid;place-items:center;width:52px;height:52px;border-radius:50%;color:#fff;font-weight:700;<?= e(gradientFor($me['name'])) ?>"><?= e(initials($me['name'])) ?></span>
            <?php endif; ?>
            <div>
              <b><?= e($me['name']) ?></b>
              <div class="muted" style="font-size:var(--step--1)">@<?= e($me['username']) ?> · <?= e(ucfirst($me['role'])) ?></div>
            </div>
          </div>
          <a class="btn btn-ghost btn-block" href="<?= url('profile') ?>"><?= icon('settings') ?> Edit profile</a>
          <?php if (isAdmin()): ?>
            <a class="btn btn-block" style="margin-top:.6rem" href="<?= url('admin') ?>"><?= icon('dashboard') ?> Admin panel</a>
          <?php endif; ?>
          <a class="btn btn-ghost btn-block" style="margin-top:.6rem" href="<?= url('logout') ?>"><?= icon('logout') ?> Sign out</a>
        </div>

        <?php if ($upcoming): ?>
          <div class="widget reveal">
            <h3>Upcoming events</h3>
            <ul class="widget-list">
              <?php foreach ($upcoming as $ev): ?>
                <li><a href="<?= url('event/' . $ev['slug']) ?>"><?= e($ev['title']) ?></a><small><?= e(fdate($ev['start_datetime'])) ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="widget reveal">
          <h3>Quick links</h3>
          <ul class="widget-list">
            <li style="border:0;padding:0"><a href="<?= url('resources') ?>"><?= icon('book') ?> Resource library</a></li>
            <li><a href="<?= url('certificates') ?>"><?= icon('award') ?> Verify a certificate</a></li>
            <li><a href="<?= url('contact') ?>"><?= icon('mail') ?> Contact the committee</a></li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>
