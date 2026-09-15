<?php pageHero($title, $subtitle, ['Membership' => 'membership', 'Apply' => null]); ?>

<section class="section">
  <div class="container container-narrow">
    <div class="stepper reveal">
      <div class="step is-current"><span class="step-label">Your details</span></div>
      <div class="step"><span class="step-label">Payment</span></div>
    </div>

    <form method="post" enctype="multipart/form-data" data-guard class="reveal">
      <?= csrfField() ?>

      <!-- plan -->
      <div class="card" style="padding:1.75rem;margin-bottom:1.5rem">
        <h3 style="font-size:var(--step-2);margin-bottom:1.2rem">Choose your plan</h3>
        <div class="wallets">
          <?php foreach ($plans as $plan): ?>
            <label class="wallet">
              <input type="radio" name="plan_id" value="<?= (int) $plan['id'] ?>"
                     <?= (int) $old['plan_id'] === (int) $plan['id'] ? 'checked' : '' ?> required>
              <span class="wallet-inner">
                <span class="check-mark"><?= icon('check') ?></span>
                <span class="wallet-name"><?= e($plan['name']) ?></span>
                <span class="wallet-number" style="font-size:var(--step-1);color:var(--text)"><?= e(taka($plan['fee'])) ?></span>
                <span class="wallet-type"><?= $plan['duration_months'] > 0 ? $plan['duration_months'] . ' months' : 'Lifetime' ?></span>
              </span>
            </label>
          <?php endforeach; ?>
        </div>
        <?php if (!empty($errors['plan_id'])): ?><p class="field-error" style="margin-top:.7rem"><?= e($errors['plan_id']) ?></p><?php endif; ?>
      </div>

      <!-- personal details -->
      <div class="card" style="padding:1.75rem;margin-bottom:1.5rem">
        <h3 style="font-size:var(--step-2);margin-bottom:1.2rem">Your details</h3>

        <div class="form-row">
          <div class="field">
            <label for="full_name">Full name <span class="req">*</span></label>
            <input class="input" type="text" id="full_name" name="full_name" required
                   value="<?= e($old['full_name']) ?>" <?= isset($errors['full_name']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['full_name'])): ?><span class="field-error"><?= e($errors['full_name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="student_id">Student ID <span class="req">*</span></label>
            <input class="input" type="text" id="student_id" name="student_id" required placeholder="e.g. 2101001"
                   value="<?= e($old['student_id']) ?>" <?= isset($errors['student_id']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['student_id'])): ?><span class="field-error"><?= e($errors['student_id']) ?></span><?php endif; ?>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="email">Email <span class="req">*</span></label>
            <input class="input" type="email" id="email" name="email" required
                   value="<?= e($old['email']) ?>" <?= isset($errors['email']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['email'])): ?><span class="field-error"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="phone">Mobile number <span class="req">*</span></label>
            <input class="input" type="tel" id="phone" name="phone" required placeholder="01712345678"
                   value="<?= e($old['phone']) ?>" <?= isset($errors['phone']) ? 'aria-invalid="true"' : '' ?>>
            <span class="field-hint">Use the number linked to the wallet you will pay from.</span>
            <?php if (isset($errors['phone'])): ?><span class="field-error"><?= e($errors['phone']) ?></span><?php endif; ?>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="department">Department <span class="req">*</span></label>
            <select class="select" id="department" name="department" required>
              <?php foreach (['CSE', 'EEE', 'ME', 'CE', 'IPE', 'TE', 'ChE', 'MSE', 'Architecture'] as $d): ?>
                <option value="<?= e($d) ?>" <?= $old['department'] === $d ? 'selected' : '' ?>><?= e($d) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['department'])): ?><span class="field-error"><?= e($errors['department']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="series">Session / Series <span class="req">*</span></label>
            <select class="select" id="series" name="series" required>
              <option value="">Select…</option>
              <?php foreach (['2025-26', '2024-25', '2023-24', '2022-23', '2021-22', '2020-21', '2019-20'] as $s): ?>
                <option value="<?= e($s) ?>" <?= $old['series'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['series'])): ?><span class="field-error"><?= e($errors['series']) ?></span><?php endif; ?>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="semester">Current semester</label>
            <select class="select" id="semester" name="semester">
              <option value="">Select…</option>
              <?php foreach (['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'] as $s): ?>
                <option value="<?= e($s) ?>" <?= $old['semester'] === $s ? 'selected' : '' ?>><?= e($s) ?> semester</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="blood_group">Blood group</label>
            <select class="select" id="blood_group" name="blood_group">
              <option value="">Select…</option>
              <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $b): ?>
                <option value="<?= e($b) ?>" <?= $old['blood_group'] === $b ? 'selected' : '' ?>><?= e($b) ?></option>
              <?php endforeach; ?>
            </select>
            <span class="field-hint">Used only for the society's emergency donor list.</span>
          </div>
        </div>

        <div class="field">
          <label for="address">Present address</label>
          <input class="input" type="text" id="address" name="address" value="<?= e($old['address']) ?>">
        </div>

        <div class="field">
          <label>Areas of interest</label>
          <div class="grid" style="--grid-gap:.6rem;grid-template-columns:repeat(auto-fit,minmax(11rem,1fr))">
            <?php
            $chosen = array_map('trim', explode(',', (string) $old['interests']));
            foreach (['Competitive Programming', 'Web Development', 'Mobile Development', 'AI & Machine Learning',
                      'Cybersecurity', 'Data Science', 'IoT & Embedded', 'UI/UX Design', 'Research'] as $int): ?>
              <label class="check">
                <input type="checkbox" name="interests[]" value="<?= e($int) ?>" <?= in_array($int, $chosen, true) ? 'checked' : '' ?>>
                <span><?= e($int) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="field">
          <label for="motivation">Why do you want to join?</label>
          <textarea class="textarea" id="motivation" name="motivation" rows="4"
                    placeholder="A couple of sentences is plenty."><?= e($old['motivation']) ?></textarea>
        </div>

        <div class="field">
          <label>Photograph</label>
          <label class="file-drop">
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            <?= icon('image') ?>
            <span><strong>Click to upload</strong> or drag a photo here</span>
            <span class="field-hint file-name">JPG, PNG or WEBP · up to 3 MB · passport style preferred</span>
            <img class="file-preview" hidden alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-height:110px;border-radius:10px;margin-top:.6rem">
          </label>
          <?php if (isset($errors['photo'])): ?><span class="field-error"><?= e($errors['photo']) ?></span><?php endif; ?>
        </div>
      </div>

      <div class="card" style="padding:1.5rem 1.75rem;margin-bottom:1.5rem">
        <label class="check">
          <input type="checkbox" name="agree" value="1" required <?= isset($_POST['agree']) ? 'checked' : '' ?>>
          <span>
            I confirm the information above is accurate, and I accept the
            <a class="link" href="<?= url('about/constitution') ?>" target="_blank">DUETCS code of conduct</a>
            set out in Article VIII of the constitution.
          </span>
        </label>
        <?php if (isset($errors['agree'])): ?><span class="field-error" style="margin-top:.5rem;display:block"><?= e($errors['agree']) ?></span><?php endif; ?>
      </div>

      <div class="btn-group">
        <button class="btn btn-lg" type="submit">Continue to payment <?= icon('arrow') ?></button>
        <a class="btn btn-ghost btn-lg" href="<?= url('membership') ?>">Back to plans</a>
      </div>
    </form>
  </div>
</section>
