<?php pageHero($title, $subtitle, ['Membership' => 'membership', 'Payment' => null]); ?>

<section class="section">
  <div class="container container-narrow">
    <div class="stepper reveal">
      <div class="step is-done"><span class="step-label">Your details</span></div>
      <div class="step is-current"><span class="step-label">Payment</span></div>
    </div>

    <div class="alert alert-info reveal">
      <?= icon('shield') ?>
      <div>
        <strong>How this works.</strong> You send the fee yourself, from your own wallet app, to the DUETCS number below.
        Then you record the transaction here. <b>This site never asks for your PIN, OTP or wallet password</b> — and
        no money is taken automatically.
      </div>
    </div>

    <form method="post" enctype="multipart/form-data" data-guard class="reveal">
      <?= csrfField() ?>

      <!-- 1. plan -->
      <div class="card" style="padding:1.75rem;margin-bottom:1.5rem">
        <h3 style="font-size:var(--step-2);margin-bottom:.4rem">1 · Confirm your plan</h3>
        <p class="muted" style="margin-bottom:1.2rem;font-size:var(--step-0)">The amount you send must match this exactly.</p>

        <div class="field">
          <label for="plan-select">Membership plan</label>
          <select class="select" id="plan-select" name="plan_id" required>
            <?php foreach ($plans as $plan): ?>
              <option value="<?= (int) $plan['id'] ?>" data-fee="<?= e($plan['fee']) ?>"
                      <?= (int) $old['plan_id'] === (int) $plan['id'] ? 'selected' : '' ?>>
                <?= e($plan['name']) ?> — <?= e(taka($plan['fee'])) ?>
                <?= $plan['duration_months'] > 0 ? '(' . $plan['duration_months'] . ' months)' : '(lifetime)' ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (isset($errors['plan_id'])): ?><span class="field-error"><?= e($errors['plan_id']) ?></span><?php endif; ?>
        </div>

        <div class="receipt-total" style="border-radius:var(--radius);border:1px solid var(--border);margin-top:.5rem">
          <span>Amount to send</span>
          <span class="amount" id="amount-display"><?= e(taka($old['amount'])) ?></span>
        </div>
        <input type="hidden" name="amount" id="amount-field" value="<?= e($old['amount']) ?>">
        <?php if (isset($errors['amount'])): ?><span class="field-error"><?= e($errors['amount']) ?></span><?php endif; ?>
      </div>

      <!-- 2. wallet -->
      <div class="card" style="padding:1.75rem;margin-bottom:1.5rem">
        <h3 style="font-size:var(--step-2);margin-bottom:.4rem">2 · Choose a wallet and pay</h3>
        <p class="muted" style="margin-bottom:1.2rem;font-size:var(--step-0)">Open your own app and send the amount to the number shown.</p>

        <div class="wallets">
          <?php foreach ($methods as $m): ?>
            <label class="wallet w-<?= e($m['code']) ?>" style="--wallet:<?= e($m['brand_color']) ?>">
              <input type="radio" name="method" value="<?= e($m['code']) ?>" <?= $old['method'] === $m['code'] ? 'checked' : '' ?> required>
              <span class="wallet-inner">
                <span class="check-mark"><?= icon('check') ?></span>
                <span class="wallet-logo"><?= e(strtoupper(substr($m['label'], 0, 1))) ?></span>
                <span class="wallet-name"><?= e($m['label']) ?></span>
                <span class="wallet-number"><?= e($m['number']) ?></span>
                <span class="wallet-type"><?= e($m['account_type']) ?></span>
              </span>
            </label>
          <?php endforeach; ?>
        </div>
        <?php if (isset($errors['method'])): ?><span class="field-error" style="margin-top:.7rem;display:block"><?= e($errors['method']) ?></span><?php endif; ?>

        <?php foreach ($methods as $m):
            $steps = array_filter(array_map('trim', explode("\n", (string) $m['instructions']))); ?>
          <div data-method-panel="<?= e($m['code']) ?>" style="--wallet:<?= e($m['brand_color']) ?>;margin-top:1.5rem" hidden>
            <div class="pay-number">
              <div>
                <span class="num"><?= e($m['number']) ?></span>
                <small>DUETCS <?= e($m['label']) ?> · <?= e($m['account_type']) ?> account</small>
              </div>
              <button class="btn btn-ghost btn-sm copy-btn" type="button" data-copy="<?= e(preg_replace('/\D/', '', $m['number'])) ?>">
                <?= icon('file') ?><span class="copy-label">Copy number</span>
              </button>
            </div>
            <ol class="pay-steps">
              <?php foreach ($steps as $s): ?><li><?= e($s) ?></li><?php endforeach; ?>
            </ol>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- 3. record the transaction -->
      <div class="card" style="padding:1.75rem;margin-bottom:1.5rem">
        <h3 style="font-size:var(--step-2);margin-bottom:.4rem">3 · Record the transaction</h3>
        <p class="muted" style="margin-bottom:1.2rem;font-size:var(--step-0)">Copy these straight from the confirmation SMS you just received.</p>

        <div class="form-row">
          <div class="field">
            <label for="sender_number">Number you paid from <span class="req">*</span></label>
            <input class="input" type="tel" id="sender_number" name="sender_number" required placeholder="01712345678"
                   value="<?= e($old['sender_number']) ?>" <?= isset($errors['sender_number']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['sender_number'])): ?><span class="field-error"><?= e($errors['sender_number']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="trx_id">Transaction ID (TrxID) <span class="req">*</span></label>
            <input class="input mono" type="text" id="trx_id" name="trx_id" required placeholder="e.g. 9F4KLM2XZQ"
                   style="text-transform:uppercase" value="<?= e($old['trx_id']) ?>"
                   <?= isset($errors['trx_id']) ? 'aria-invalid="true"' : '' ?>>
            <span class="field-hint">The reference code in your bKash/Nagad/Rocket confirmation message.</span>
            <?php if (isset($errors['trx_id'])): ?><span class="field-error"><?= e($errors['trx_id']) ?></span><?php endif; ?>
          </div>
        </div>

        <div class="field">
          <label>Payment screenshot <span class="muted">(optional, speeds up verification)</span></label>
          <label class="file-drop">
            <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp">
            <?= icon('image') ?>
            <span><strong>Upload the confirmation screenshot</strong></span>
            <span class="field-hint file-name">JPG, PNG or WEBP · up to 3 MB</span>
            <img class="file-preview" hidden alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-height:130px;border-radius:10px;margin-top:.6rem">
          </label>
          <?php if (isset($errors['screenshot'])): ?><span class="field-error"><?= e($errors['screenshot']) ?></span><?php endif; ?>
        </div>
      </div>

      <?php if ($note): ?>
        <div class="alert alert-warning reveal"><?= icon('clock') ?><span><?= e($note) ?></span></div>
      <?php endif; ?>

      <div class="btn-group">
        <button class="btn btn-lg" type="submit"><?= icon('check') ?> Submit for verification</button>
        <a class="btn btn-ghost btn-lg" href="<?= url('membership/apply') ?>">Edit my details</a>
      </div>
    </form>
  </div>
</section>
