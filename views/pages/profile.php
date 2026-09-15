<?php pageHero($title, $subtitle, ['Dashboard' => 'dashboard', 'Profile' => null]); ?>

<section class="section">
  <div class="container container-narrow">

    <form class="card reveal" method="post" enctype="multipart/form-data" data-guard style="padding:1.9rem;margin-bottom:1.5rem">
      <?= csrfField() ?>
      <h2 style="font-size:var(--step-2);margin-bottom:1.4rem">Account details</h2>

      <div class="form-row">
        <div class="field">
          <label for="name">Full name</label>
          <input class="input" type="text" id="name" name="name" required value="<?= e($old['name']) ?>"
                 <?= isset($errors['name']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['name'])): ?><span class="field-error"><?= e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email" required value="<?= e($old['email']) ?>"
                 <?= isset($errors['email']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['email'])): ?><span class="field-error"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>
      </div>

      <div class="field">
        <label for="phone">Mobile number</label>
        <input class="input" type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>"
               <?= isset($errors['phone']) ? 'aria-invalid="true"' : '' ?>>
        <?php if (isset($errors['phone'])): ?><span class="field-error"><?= e($errors['phone']) ?></span><?php endif; ?>
      </div>

      <div class="field">
        <label for="bio">Short bio</label>
        <textarea class="textarea" id="bio" name="bio" rows="3" maxlength="255"><?= e($old['bio']) ?></textarea>
      </div>

      <div class="field">
        <label>Profile photo</label>
        <label class="file-drop">
          <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp">
          <?= icon('image') ?>
          <span><strong>Click to upload</strong> or drag an image here</span>
          <span class="field-hint file-name">JPG, PNG or WEBP · up to 3 MB</span>
          <img class="file-preview" hidden alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-height:110px;border-radius:10px;margin-top:.6rem">
        </label>
        <?php if (isset($errors['avatar'])): ?><span class="field-error"><?= e($errors['avatar']) ?></span><?php endif; ?>
      </div>

      <button class="btn" type="submit"><?= icon('check') ?> Save changes</button>
    </form>

    <form class="card reveal" method="post" data-guard style="padding:1.9rem">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="password">
      <h2 style="font-size:var(--step-2);margin-bottom:1.4rem">Change password</h2>

      <div class="field">
        <label for="current_password">Current password</label>
        <input class="input" type="password" id="current_password" name="current_password" required autocomplete="current-password"
               <?= isset($errors['current_password']) ? 'aria-invalid="true"' : '' ?>>
        <?php if (isset($errors['current_password'])): ?><span class="field-error"><?= e($errors['current_password']) ?></span><?php endif; ?>
      </div>

      <div class="form-row">
        <div class="field">
          <label for="new_password">New password</label>
          <input class="input" type="password" id="new_password" name="new_password" required autocomplete="new-password"
                 <?= isset($errors['new_password']) ? 'aria-invalid="true"' : '' ?>>
          <span class="field-hint">At least 8 characters with a letter and a number.</span>
          <?php if (isset($errors['new_password'])): ?><span class="field-error"><?= e($errors['new_password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
          <label for="new_password_confirm">Confirm new password</label>
          <input class="input" type="password" id="new_password_confirm" name="new_password_confirm" required autocomplete="new-password"
                 <?= isset($errors['new_password_confirm']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['new_password_confirm'])): ?><span class="field-error"><?= e($errors['new_password_confirm']) ?></span><?php endif; ?>
        </div>
      </div>

      <button class="btn" type="submit"><?= icon('shield') ?> Update password</button>
    </form>
  </div>
</section>
