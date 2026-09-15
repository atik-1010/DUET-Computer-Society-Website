<div class="auth-wrap">
  <div class="auth-visual">
    <a class="brand" href="<?= url() ?>" style="margin-bottom:1rem">
      <span class="brand-mark" style="background:rgba(255,255,255,.16)"><?= icon('code') ?></span>
      <span class="brand-text">
        <span class="brand-name" style="color:#fff"><?= e(SITE_SHORT) ?></span>
        <span class="brand-sub" style="color:rgba(255,255,255,.7)">Computer Society</span>
      </span>
    </a>
    <h2>Start where everyone starts.</h2>
    <p>Create a free account. You can browse everything without one — but membership, certificates and event registration are tied to it.</p>
    <ul class="auth-points">
      <li><?= icon('check') ?><span>Free to create, no membership fee required</span></li>
      <li><?= icon('check') ?><span>Apply for membership and pay by bKash, Nagad or Rocket</span></li>
      <li><?= icon('check') ?><span>Track your application without emailing anyone</span></li>
      <li><?= icon('check') ?><span>Open to every DUET department and series</span></li>
    </ul>
  </div>

  <div class="auth-form">
    <div class="auth-card">
      <h1>Create your account</h1>
      <p>Takes about a minute.</p>

      <form method="post" data-guard>
        <?= csrfField() ?>

        <div class="field">
          <label for="name">Full name</label>
          <input class="input" type="text" id="name" name="name" required autofocus autocomplete="name"
                 value="<?= e($old['name']) ?>" <?= isset($errors['name']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['name'])): ?><span class="field-error"><?= e($errors['name']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label for="username">Username</label>
          <input class="input" type="text" id="username" name="username" required autocomplete="username"
                 value="<?= e($old['username']) ?>" <?= isset($errors['username']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['username'])): ?><span class="field-error"><?= e($errors['username']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email" required autocomplete="email"
                 value="<?= e($old['email']) ?>" <?= isset($errors['email']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['email'])): ?><span class="field-error"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label for="phone">Mobile number <span class="muted">(optional)</span></label>
          <input class="input" type="tel" id="phone" name="phone" placeholder="01712345678" autocomplete="tel"
                 value="<?= e($old['phone']) ?>" <?= isset($errors['phone']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['phone'])): ?><span class="field-error"><?= e($errors['phone']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input class="input" type="password" id="password" name="password" required autocomplete="new-password"
                 <?= isset($errors['password']) ? 'aria-invalid="true"' : '' ?>>
          <span class="field-hint">At least 8 characters, including a letter and a number.</span>
          <?php if (isset($errors['password'])): ?><span class="field-error"><?= e($errors['password']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label for="password_confirm">Confirm password</label>
          <input class="input" type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password"
                 <?= isset($errors['password_confirm']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['password_confirm'])): ?><span class="field-error"><?= e($errors['password_confirm']) ?></span><?php endif; ?>
        </div>

        <div class="field">
          <label class="check">
            <input type="checkbox" name="agree" value="1" required>
            <span>I accept the <a class="link" href="<?= url('about/constitution') ?>" target="_blank">DUETCS code of conduct</a>.</span>
          </label>
          <?php if (isset($errors['agree'])): ?><span class="field-error"><?= e($errors['agree']) ?></span><?php endif; ?>
        </div>

        <button class="btn btn-block btn-lg" type="submit">Create account <?= icon('arrow') ?></button>
      </form>

      <p class="auth-alt">Already registered? <a class="link" href="<?= url('login') ?>">Sign in</a></p>
    </div>
  </div>
</div>
