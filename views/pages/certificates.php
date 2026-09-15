<?php pageHero($title, $subtitle, ['Certificates' => 'certificates']); ?>

<section class="section">
  <div class="container container-narrow">
    <div class="verify-box reveal">
      <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.4rem">
        <span style="display:grid;place-items:center;width:52px;height:52px;border-radius:15px;background:var(--gradient-brand);color:#fff;flex:none"><?= icon('award') ?></span>
        <div>
          <h2 style="font-size:var(--step-2)">Certificate verification</h2>
          <p class="muted" style="font-size:var(--step-0)"><?= number_format($total) ?> certificates issued to date</p>
        </div>
      </div>

      <form method="post">
        <?= csrfField() ?>
        <div class="field">
          <label for="code">Verification code or certificate number</label>
          <div class="input-group">
            <?= icon('search') ?>
            <input class="input mono" type="text" id="code" name="code" required
                   placeholder="e.g. A7K2M9 or DCS-2026-IUPC-0011"
                   style="text-transform:uppercase" value="<?= e($code) ?>">
          </div>
          <span class="field-hint">Printed at the bottom of every DUETCS certificate.</span>
        </div>
        <button class="btn btn-block btn-lg" type="submit"><?= icon('shield') ?> Verify certificate</button>
      </form>
    </div>

    <?php if ($searched): ?>
      <div style="margin-top:2rem">
        <?php if ($result && !$result['is_revoked']): ?>
          <div class="cert-result reveal">
            <div class="cert-seal"><?= icon('check') ?></div>
            <h2 style="font-size:var(--step-2);margin-bottom:.4rem">Genuine certificate</h2>
            <p class="soft" style="margin-bottom:1.6rem">This certificate was issued by <?= e(SITE_NAME) ?> and is valid.</p>

            <dl class="receipt-body" style="padding:0">
              <div class="receipt-row"><dt>Certificate number</dt><dd class="mono"><?= e($result['cert_no']) ?></dd></div>
              <div class="receipt-row"><dt>Verification code</dt><dd class="mono"><?= e($result['verify_code']) ?></dd></div>
              <div class="receipt-row"><dt>Awarded to</dt><dd><?= e($result['holder_name']) ?></dd></div>
              <?php if ($result['student_id']): ?><div class="receipt-row"><dt>Student ID</dt><dd><?= e($result['student_id']) ?></dd></div><?php endif; ?>
              <div class="receipt-row"><dt>Awarded for</dt><dd><?= e($result['title']) ?></dd></div>
              <div class="receipt-row"><dt>Type</dt><dd><span class="badge badge-brand"><?= e(ucfirst($result['type'])) ?></span></dd></div>
              <?php if ($result['position']): ?><div class="receipt-row"><dt>Position</dt><dd><b><?= e($result['position']) ?></b></dd></div><?php endif; ?>
              <div class="receipt-row"><dt>Issued on</dt><dd><?= e(fdate($result['issued_on'])) ?></dd></div>
            </dl>
          </div>
        <?php elseif ($result && $result['is_revoked']): ?>
          <div class="cert-result is-invalid reveal">
            <div class="cert-seal"><?= icon('close') ?></div>
            <h2 style="font-size:var(--step-2);margin-bottom:.4rem">This certificate has been revoked</h2>
            <p class="soft">Certificate <b class="mono"><?= e($result['cert_no']) ?></b> was issued but has since been withdrawn by the committee. Contact DUETCS if you believe this is an error.</p>
          </div>
        <?php else: ?>
          <div class="cert-result is-invalid reveal">
            <div class="cert-seal"><?= icon('close') ?></div>
            <h2 style="font-size:var(--step-2);margin-bottom:.4rem">No certificate found</h2>
            <p class="soft" style="margin-bottom:1.2rem">
              Nothing matches <b class="mono"><?= e($code) ?></b>. Check the code for typos —
              the letter O and the digit 0 are easy to confuse. If it still does not verify, the certificate may not be genuine.
            </p>
            <a class="btn btn-ghost" href="<?= url('contact') ?>"><?= icon('mail') ?> Report this to the committee</a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($recent): ?>
      <div style="margin-top:3.5rem">
        <h3 style="font-size:var(--step-2);margin-bottom:1.2rem">Recently issued</h3>
        <div class="table-wrap reveal">
          <table class="table">
            <thead><tr><th>Certificate no.</th><th>Holder</th><th>Awarded for</th><th>Type</th><th>Issued</th></tr></thead>
            <tbody>
              <?php foreach ($recent as $c): ?>
                <tr>
                  <td class="mono" style="font-size:var(--step--1)"><?= e($c['cert_no']) ?></td>
                  <td><?= e($c['holder_name']) ?></td>
                  <td><?= e($c['title']) ?></td>
                  <td><span class="badge"><?= e(ucfirst($c['type'])) ?></span></td>
                  <td class="muted"><?= e(fdate($c['issued_on'])) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
