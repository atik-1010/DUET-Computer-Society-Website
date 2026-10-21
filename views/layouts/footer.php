</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-about">
        
<a class="brand" href="<?= url() ?>">
  <span class="brand-mark">
    <img
      src="<?= asset('img/logo.png') ?>"
      alt="DUETCS Logo"
      class="footer-logo"
    >
  </span>
  <span class="brand-text">
    <span class="brand-name"><?= e(SITE_SHORT) ?></span>
    <span class="brand-sub">Computer Society</span>
  </span>
</a>

        <p><?= e(SITE_DESC) ?></p>
        <div class="social-row">
          <a href="<?= e(setting('facebook_url', '#')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a>
          <a href="<?= e(setting('github_url', '#')) ?>" target="_blank" rel="noopener" aria-label="GitHub"><?= icon('github') ?></a>
          <a href="<?= e(setting('linkedin_url', '#')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin') ?></a>
          <a href="<?= e(setting('youtube_url', '#')) ?>" target="_blank" rel="noopener" aria-label="YouTube"><?= icon('play') ?></a>
        </div>

        <form class="newsletter" method="post" action="<?= url('contact') ?>">
          <?= csrfField() ?>
          <input type="hidden" name="action" value="subscribe">
          <label class="sr-only" for="nl-email">Email address</label>
          <input class="input" type="email" id="nl-email" name="email" placeholder="your.email@duet.ac.bd" required>
          <button class="btn btn-sm" type="submit" aria-label="Subscribe"><?= icon('arrow') ?></button>
        </form>
      </div>

      <div>
        <h4>Society</h4>
        <ul class="footer-links">
          <li><a href="<?= url('about') ?>">About DUETCS</a></li>
          <li><a href="<?= url('about/history') ?>">History</a></li>
          <li><a href="<?= url('about/mission') ?>">Mission &amp; Vision</a></li>
          <li><a href="<?= url('about/constitution') ?>">Constitution</a></li>
          <li><a href="<?= url('committee') ?>">Executive Committee</a></li>
          <li><a href="<?= url('committee/advisors') ?>">Advisors</a></li>
        </ul>
      </div>

      <div>
        <h4>Programmes</h4>
        <ul class="footer-links">
          <li><a href="<?= url('events/upcoming') ?>">Upcoming Events</a></li>
          <li><a href="<?= url('activities/programming') ?>">Programming</a></li>
          <li><a href="<?= url('activities/ai-ml') ?>">AI &amp; ML</a></li>
          <li><a href="<?= url('activities/cybersecurity') ?>">Cybersecurity</a></li>
          <li><a href="<?= url('competitions/iupc') ?>">IUPC</a></li>
          <li><a href="<?= url('competitions/hackathon') ?>">Hackathon</a></li>
        </ul>
      </div>

      <div>
        <h4>Explore</h4>
        <ul class="footer-links">
          <li><a href="<?= url('projects') ?>">Projects</a></li>
          <li><a href="<?= url('blog') ?>">Blog</a></li>
          <li><a href="<?= url('gallery/photos') ?>">Gallery</a></li>
          <li><a href="<?= url('resources') ?>">Resources</a></li>
          <li><a href="<?= url('membership') ?>">Membership</a></li>
          <li><a href="<?= url('certificates') ?>">Verify Certificate</a></li>
        </ul>
      </div>

      <div>
        <h4>Contact</h4>
        <ul class="footer-contact">
          <li><?= icon('pin') ?><span><?= e(setting('site_address', SITE_ADDRESS)) ?></span></li>
          <li><?= icon('mail') ?><a href="mailto:<?= e(setting('site_email', SITE_EMAIL)) ?>"><?= e(setting('site_email', SITE_EMAIL)) ?></a></li>
          <li><?= icon('phone') ?><a href="tel:<?= e(preg_replace('/\s+/', '', setting('site_phone', SITE_PHONE))) ?>"><?= e(setting('site_phone', SITE_PHONE)) ?></a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</p>
      <p>Built by Atikur Rahman Bappy (GS, 2024-25) · <a href="<?= url('contact') ?>">Report an issue</a></p>
    </div>
  </div>
</footer>

<button class="to-top" type="button" aria-label="Back to top"><?= icon('arrow') ?></button>

<!-- Image lightbox -->
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer">
  <button class="lightbox-close icon-btn" type="button" aria-label="Close"><?= icon('close') ?></button>
  <button class="lightbox-nav lightbox-prev" type="button" aria-label="Previous image"><?= icon('arrowLeft') ?></button>
  <button class="lightbox-nav lightbox-next" type="button" aria-label="Next image"><?= icon('arrow') ?></button>
  <div>
    <img id="lightbox-img" src="" alt="">
    <p class="lightbox-cap" id="lightbox-cap"></p>
  </div>
</div>

<!-- Video modal -->
<div class="lightbox" id="video-modal" role="dialog" aria-modal="true" aria-label="Video player">
  <button class="lightbox-close icon-btn" type="button" aria-label="Close"><?= icon('close') ?></button>
  <div style="width:min(100%,960px);aspect-ratio:16/9">
    <iframe id="video-frame" src="" title="Video" style="width:100%;height:100%;border:0;border-radius:16px"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
  </div>
</div>

<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
