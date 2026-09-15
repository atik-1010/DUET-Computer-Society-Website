<?php pageHero($title, $subtitle, ['Contact' => 'contact']); ?>

<section class="section">
  <div class="container">
    <div class="grid" style="--grid-gap:2.5rem;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);align-items:start">

      <form class="card reveal" method="post" data-guard style="padding:1.9rem">
        <?= csrfField() ?>
        <h2 style="font-size:var(--step-2);margin-bottom:.4rem">Send us a message</h2>
        <p class="muted" style="margin-bottom:1.6rem;font-size:var(--step-0)">The committee reads every message and usually replies within two working days.</p>

        <div class="form-row">
          <div class="field">
            <label for="name">Your name <span class="req">*</span></label>
            <input class="input" type="text" id="name" name="name" required value="<?= e($old['name'] ?? '') ?>"
                   <?= isset($errors['name']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['name'])): ?><span class="field-error"><?= e($errors['name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="email">Email <span class="req">*</span></label>
            <input class="input" type="email" id="email" name="email" required value="<?= e($old['email'] ?? '') ?>"
                   <?= isset($errors['email']) ? 'aria-invalid="true"' : '' ?>>
            <?php if (isset($errors['email'])): ?><span class="field-error"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="phone">Mobile <span class="muted">(optional)</span></label>
            <input class="input" type="tel" id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>">
          </div>
          <div class="field">
            <label for="subject">Subject <span class="req">*</span></label>
            <select class="select" id="subject" name="subject" required>
              <?php
              $subjects = ['Membership question', 'Payment or verification issue', 'Event registration',
                           'Sponsorship or partnership', 'Speaking or workshop request', 'Report a problem with the site', 'Something else'];
              foreach ($subjects as $s): ?>
                <option value="<?= e($s) ?>" <?= ($old['subject'] ?? '') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['subject'])): ?><span class="field-error"><?= e($errors['subject']) ?></span><?php endif; ?>
          </div>
        </div>

        <div class="field">
          <label for="message">Message <span class="req">*</span></label>
          <textarea class="textarea" id="message" name="message" rows="6" required
                    <?= isset($errors['message']) ? 'aria-invalid="true"' : '' ?>><?= e($old['message'] ?? '') ?></textarea>
          <?php if (isset($errors['message'])): ?><span class="field-error"><?= e($errors['message']) ?></span><?php endif; ?>
        </div>

        <button class="btn btn-lg" type="submit"><?= icon('mail') ?> Send message</button>
      </form>

      <aside>
        <div class="widget reveal">
          <h3>Reach us directly</h3>
          <ul class="footer-contact" style="--x:0">
            <li style="color:var(--text-soft)"><?= icon('pin') ?><span><?= e(setting('site_address', SITE_ADDRESS)) ?></span></li>
            <li style="color:var(--text-soft)"><?= icon('mail') ?><a class="link" href="mailto:<?= e(setting('site_email', SITE_EMAIL)) ?>"><?= e(setting('site_email', SITE_EMAIL)) ?></a></li>
            <li style="color:var(--text-soft)"><?= icon('phone') ?><a class="link" href="tel:<?= e(preg_replace('/\s+/', '', setting('site_phone', SITE_PHONE))) ?>"><?= e(setting('site_phone', SITE_PHONE)) ?></a></li>
          </ul>
        </div>

        <div class="widget reveal">
          <h3>Office hours</h3>
          <dl>
            <div class="receipt-row"><dt>Sunday – Thursday</dt><dd>2:00 – 6:00 PM</dd></div>
            <div class="receipt-row"><dt>Friday</dt><dd>Closed</dd></div>
            <div class="receipt-row"><dt>Saturday</dt><dd>By appointment</dd></div>
          </dl>
          <p class="muted" style="margin-top:.9rem;font-size:var(--step--1)">DUETCS Room, Department of CSE, Academic Building.</p>
        </div>

        <div class="widget reveal">
          <h3>Find us</h3>
          <div style="border-radius:var(--radius);overflow:hidden;aspect-ratio:4/3;background:var(--surface-2)">
            <iframe src="<?= e(setting('map_embed', 'https://www.google.com/maps?q=DUET+Gazipur&output=embed')) ?>"
                    title="DUET campus map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    style="width:100%;height:100%;border:0"></iframe>
          </div>
        </div>

        <div class="widget reveal">
          <h3>Follow DUETCS</h3>
          <div class="social-row">
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)" href="<?= e(setting('facebook_url', '#')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a>
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)" href="<?= e(setting('github_url', '#')) ?>" target="_blank" rel="noopener" aria-label="GitHub"><?= icon('github') ?></a>
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)" href="<?= e(setting('linkedin_url', '#')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin') ?></a>
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)" href="<?= e(setting('youtube_url', '#')) ?>" target="_blank" rel="noopener" aria-label="YouTube"><?= icon('play') ?></a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>
