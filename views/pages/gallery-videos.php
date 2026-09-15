<?php pageHero($title, $subtitle, ['Gallery' => 'gallery', 'Videos' => null]); ?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <a class="filter-chip" href="<?= url('gallery') ?>"><?= icon('image') ?> Overview</a>
      <a class="filter-chip" href="<?= url('gallery/photos') ?>"><?= icon('image') ?> Photos</a>
      <a class="filter-chip is-active" href="<?= url('gallery/videos') ?>"><?= icon('play') ?> Videos</a>
    </div>

    <?php if (!$videos): ?>
      <?php emptyState('No videos yet', 'Session recordings are published after each event.', 'play'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($videos as $v): ?>
          <article class="card video-card reveal" data-video="<?= e($v['youtube_id']) ?>" role="button" tabindex="0">
            <div class="video-thumb">
              <img src="https://i.ytimg.com/vi/<?= e($v['youtube_id']) ?>/hqdefault.jpg" alt="" loading="lazy">
              <span class="video-play"><span><?= icon('play') ?></span></span>
              <?php if ($v['duration']): ?><span class="video-duration"><?= e($v['duration']) ?></span><?php endif; ?>
            </div>
            <div class="card-body">
              <h3 class="card-title"><?= e($v['title']) ?></h3>
              <p class="card-text"><?= e(excerpt($v['description'], 110)) ?></p>
            </div>
            <div class="card-foot">
              <span><?= icon('calendar') ?> <?= e(fdate($v['published_at'])) ?></span>
              <span class="link">Watch <?= icon('play') ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
