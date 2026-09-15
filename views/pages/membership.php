<?php pageHero($title, $subtitle, ['Membership' => 'membership']); ?>

<?php if ($member): ?>
  <div class="container" style="margin-top:2rem">
    <div class="alert alert-info reveal">
      <?= icon('sparkle') ?>
      <div>
        <strong>You already have an application on file.</strong>
        Its status is <b><?= e(statusLabel($member['status'])) ?></b>.
        <a class="link" href="<?= url('membership/status') ?>">View your application <?= icon('arrow') ?></a>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- plans -->
<section class="section">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Choose a plan</span>
      <h2>Three ways to join</h2>
      <p>All plans are open to every enrolled DUET student, from any department and any series.</p>
    </div>

    <div class="plans">
      <?php foreach ($plans as $plan):
          $benefits = array_filter(array_map('trim', explode("\n", (string) $plan['benefits']))); ?>
        <div class="plan reveal <?= $plan['is_featured'] ? 'is-featured' : '' ?>">
          <h3><?= e($plan['name']) ?></h3>
          <p class="plan-tagline"><?= e($plan['tagline']) ?></p>
          <div class="plan-price">
            <span class="amount"><?= e(taka($plan['fee'])) ?></span>
            <span class="per"><?= $plan['duration_months'] > 0 ? '/ ' . ($plan['duration_months'] === 12 ? 'year' : $plan['duration_months'] . ' months') : 'one-time' ?></span>
          </div>
          <ul class="plan-benefits">
            <?php foreach ($benefits as $b): ?>
              <li><?= icon('check') ?><span><?= e($b) ?></span></li>
            <?php endforeach; ?>
          </ul>
          <a class="btn <?= $plan['is_featured'] ? '' : 'btn-ghost' ?> btn-block" href="<?= url('membership/apply') ?>">
            Apply with <?= e($plan['name']) ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- how it works -->
<section class="section section-alt">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">How it works</span>
      <h2>Four steps, about five minutes</h2>
      <p>We verify every payment by hand against the wallet statement, so nothing is charged automatically and no card details are ever collected.</p>
    </div>

    <div class="grid grid-4">
      <?php
      $steps = [
          ['users',    'Create an account', 'Register with your name, email and a password. Your application is linked to this account.'],
          ['file',     'Fill the form',     'Student ID, department, series and your areas of interest. Two minutes at most.'],
          ['wallet',   'Pay from your app', 'Send the fee from your own bKash, Nagad or Rocket app to the society number.'],
          ['check',    'Submit the TrxID',  'Enter the transaction ID from your SMS. The Treasurer verifies it, usually within 24 hours.'],
      ];
      foreach ($steps as $i => [$ic, $h, $t]): ?>
        <div class="feature reveal">
          <span class="feature-icon"><?= icon($ic) ?></span>
          <span class="mono muted" style="font-size:var(--step--1);letter-spacing:.12em">STEP <?= $i + 1 ?></span>
          <h3><?= e($h) ?></h3>
          <p><?= e($t) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- payment methods -->
<section class="section">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Payment</span>
      <h2>Pay with bKash, Nagad or Rocket</h2>
      <p>You pay from your own wallet app, exactly as you would to any other number. This website never asks for your PIN, your wallet password or any card details.</p>
    </div>

    <div class="grid grid-3">
      <?php foreach ($methods as $m):
          $steps = array_filter(array_map('trim', explode("\n", (string) $m['instructions']))); ?>
        <div class="card reveal w-<?= e($m['code']) ?>" style="--wallet:<?= e($m['brand_color']) ?>">
          <div class="card-body">
            <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:.4rem">
              <span class="wallet-logo" style="width:46px;height:46px;font-size:1.2rem"><?= e(strtoupper(substr($m['label'], 0, 1))) ?></span>
              <div>
                <h3 class="card-title"><?= e($m['label']) ?></h3>
                <span class="wallet-type"><?= e($m['account_type']) ?> account</span>
              </div>
            </div>

            <div class="pay-number">
              <div>
                <span class="num"><?= e($m['number']) ?></span>
                <small>DUETCS receiving number</small>
              </div>
              <button class="btn btn-ghost btn-sm copy-btn" type="button" data-copy="<?= e(preg_replace('/\D/', '', $m['number'])) ?>">
                <?= icon('file') ?><span class="copy-label">Copy</span>
              </button>
            </div>

            <ol class="pay-steps">
              <?php foreach (array_slice($steps, 0, 5) as $s): ?><li><?= e($s) ?></li><?php endforeach; ?>
            </ol>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="alert alert-warning reveal" style="margin-top:2rem;max-width:56rem;margin-inline:auto">
      <?= icon('shield') ?>
      <div>
        <strong>Keep your confirmation SMS.</strong>
        DUETCS will never ask for your wallet PIN or a one-time code, by phone, message or on this site.
        If anyone asks you for those in the society's name, report it to the committee immediately.
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section-alt">
  <div class="container container-narrow">
    <div class="section-head center reveal">
      <span class="eyebrow">Questions</span>
      <h2>Before you apply</h2>
    </div>

    <div class="stack" style="--stack-gap:1rem">
      <?php
      $faqs = [
          ['Do I need to know how to code?',
           'No. A large share of our most active members joined in their first semester with nothing beyond a polytechnic C course. Every wing runs a beginner track that assumes no prior knowledge.'],
          ['Is membership open to non-CSE departments?',
           'Yes, entirely. Members from EEE, ME, CE, IPE, TE and every other department are on the Executive Committee and in our contest teams.'],
          ['How long does approval take?',
           'The Treasurer verifies mobile wallet transactions manually against the account statement, usually within 24 hours on working days. You will see the status change on your dashboard.'],
          ['What if I paid but entered the wrong transaction ID?',
           'Your payment will be marked rejected with a note, and you can submit a corrected transaction ID from your dashboard. No second payment is needed.'],
          ['Can I upgrade my plan later?',
           'Yes. Contact the Treasurer through the contact form and pay the difference; your existing membership period carries over.'],
          ['Are fees refundable?',
           'No. Under Article 3.5 of the constitution, fees are non-refundable once membership is approved. They fund the events the society runs.'],
      ];
      foreach ($faqs as [$q, $a]): ?>
        <details class="card reveal" style="padding:1.2rem 1.5rem">
          <summary style="cursor:pointer;font-weight:600;font-size:var(--step-1);list-style:none;display:flex;justify-content:space-between;gap:1rem;align-items:center">
            <?= e($q) ?>
            <?= icon('plus') ?>
          </summary>
          <p class="soft" style="margin-top:.9rem"><?= e($a) ?></p>
        </details>
      <?php endforeach; ?>
    </div>

    <div class="center reveal" style="margin-top:2.5rem">
      <a class="btn btn-lg" href="<?= url('membership/apply') ?>"><?= icon('sparkle') ?> Start your application</a>
    </div>
  </div>
</section>
