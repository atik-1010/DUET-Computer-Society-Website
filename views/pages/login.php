<div class="auth-wrap">
  <div class="auth-visual">
    <a class="brand" href="<?= url() ?>" style="margin-bottom:1rem">
      <span class="brand-mark" style="background:rgba(255,255,255,.16)"><?= icon('code') ?></span>
      <span class="brand-text">
        <span class="brand-name" style="color:#fff"><?= e(SITE_SHORT) ?></span>
        <span class="brand-sub" style="color:rgba(255,255,255,.7)">Computer Society</span>
      </span>
    </a>
    <h2>Welcome back to DUETCS.</h2>
    <p>Sign in to track your membership, view your certificates and manage your account.</p>
    <ul class="auth-points">
      <li><?= icon('check') ?><span>See your membership and payment status in real time</span></li>
      <li><?= icon('check') ?><span>Download certificates issued in your name</span></li>
      <li><?= icon('check') ?><span>Priority registration for limited-seat events</span></li>
      <li><?= icon('check') ?><span>Access the member resource library</span></li>
    </ul>
  </div>

  <div class="auth-form">
    <div class="auth-card">
      <h1>Sign in</h1>
      <p>Use the username or email you registered with.</p>

      <form method="post" data-guard>
        <?= csrfField() ?>
        <div class="field">
          <label for="identifier">Username or email</label>
          <div class="input-group">
            <?= icon('users') ?>
            <input class="input" type="text" id="identifier" name="identifier" required autofocus
                   autocomplete="username" value="<?= e($old['identifier']) ?>"
                   <?= isset($errors['identifier']) ? 'aria-invalid="true"' : '' ?>>
          </div>
          <?php if (isset($errors['identifier'])): ?><span class="field-error"><?= e($errors['identifier']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-group">
            <?= icon('shield') ?>
            <input class="input" type="password" id="password" name="password" required autocomplete="current-password">
          </div>
        </div>

        <button class="btn btn-block btn-lg" type="submit">Sign in <?= icon('arrow') ?></button>
      </form>

      <p class="auth-alt">
        No account yet? <a class="link" href="<?= url('register') ?>">Create one</a>
      </p>
      <p class="auth-alt" style="margin-top:.6rem">
        <a class="link" href="<?= url() ?>"><?= icon('arrowLeft') ?> Back to the website</a>
      </p>
    </div>
  </div>
</div>
