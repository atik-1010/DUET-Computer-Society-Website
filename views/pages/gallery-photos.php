<?php pageHero($title, $subtitle, ['Gallery' => 'gallery', 'Photos' => null]); ?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <a class="filter-chip" href="<?= url('gallery') ?>"><?= icon('image') ?> Overview</a>
      <a class="filter-chip is-active" href="<?= url('gallery/photos') ?>"><?= icon('image') ?> Photos</a>
      <a class="filter-chip" href="<?= url('gallery/videos') ?>"><?= icon('play') ?> Videos</a>
    </div>

    <?php if (!$albums): ?>
      <?php emptyState('No albums yet', 'Albums are published after each event.', 'image'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($albums as $al): ?>
          <a class="card reveal" href="<?= url('gallery/photos/' . $al['slug']) ?>">
            <?php mediaBox($al['cover'], 'gallery', $al['title']); ?>
            <div class="card-body">
              <h3 class="card-title"><?= e($al['title']) ?></h3>
              <p class="card-text"><?= e(excerpt($al['description'], 110)) ?></p>
            </div>
            <div class="card-foot">
              <span><?= icon('calendar') ?> <?= e(fdate($al['album_date'])) ?></span>
              <span><?= icon('image') ?> <?= (int) $al['photo_count'] ?> photos</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
