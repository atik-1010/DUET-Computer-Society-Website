<?php pageHero($album['title'], $album['description'] ?? '', ['Gallery' => 'gallery', 'Photos' => 'gallery/photos', $album['title'] => null]); ?>

<section class="section">
  <div class="container">
    <p class="muted reveal" style="margin-bottom:1.5rem">
      <?= icon('calendar') ?> <?= e(fdate($album['album_date'])) ?> · <?= count($photos) ?> photographs ·
      <span class="muted">Click any photograph to open it full size</span>
    </p>

    <?php if (!$photos): ?>
      <?php emptyState('This album is empty', 'Photographs are uploaded from the admin panel.', 'image'); ?>
    <?php else: ?>
      <div class="gallery-grid reveal">
        <?php foreach ($photos as $ph):
            $src = upload($ph['image'], 'gallery');
            if ($src === '') continue; ?>
          <figure class="gallery-item">
            <img src="<?= e($src) ?>" alt="<?= e($ph['caption']) ?>" loading="lazy">
            <?php if ($ph['caption']): ?><figcaption><?= e($ph['caption']) ?></figcaption><?php endif; ?>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($others): ?>
      <div style="margin-top:4rem">
        <div class="section-head reveal"><h2 style="font-size:var(--step-3)">Other albums</h2></div>
        <div class="grid grid-4">
          <?php foreach ($others as $al): ?>
            <a class="card reveal" href="<?= url('gallery/photos/' . $al['slug']) ?>">
              <?php mediaBox($al['cover'], 'gallery', $al['title']); ?>
              <div class="card-body"><h3 class="card-title" style="font-size:var(--step-0)"><?= e($al['title']) ?></h3></div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
