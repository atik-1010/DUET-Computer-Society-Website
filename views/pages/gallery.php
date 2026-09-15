<?php pageHero($title, $subtitle, ['Gallery' => 'gallery']); ?>

<section class="section">
  <div class="container">
    <div class="filter-row reveal">
      <a class="filter-chip is-active" href="<?= url('gallery') ?>"><?= icon('image') ?> Overview</a>
      <a class="filter-chip" href="<?= url('gallery/photos') ?>"><?= icon('image') ?> Photos</a>
      <a class="filter-chip" href="<?= url('gallery/videos') ?>"><?= icon('play') ?> Videos</a>
    </div>

    <div class="section-head reveal"><h2 style="font-size:var(--step-3)">Photo albums</h2></div>
    <?php if (!$albums): ?>
      <?php emptyState('No albums yet', 'Albums are published after each event.', 'image'); ?>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($albums as $al): ?>
          <a class="card reveal" href="<?= url('gallery/photos/' . $al['slug']) ?>">
            <?php mediaBox($al['cover'], 'gallery', $al['title']); ?>
            <div class="card-body">
              <h3 class="card-title"><?= e($al['title']) ?></h3>
              <p class="card-text"><?= e(excerpt($al['description'], 100)) ?></p>
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

<?php if ($videos): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal" style="display:flex;flex-wrap:wrap;align-items:end;justify-content:space-between;gap:1.5rem;max-width:none">
      <h2 style="font-size:var(--step-3)">Recent videos</h2>
      <a class="btn btn-ghost" href="<?= url('gallery/videos') ?>">All videos <?= icon('arrow') ?></a>
    </div>
    <div class="grid grid-3">
      <?php foreach ($videos as $v): ?>
        <article class="card video-card reveal" data-video="<?= e($v['youtube_id']) ?>">
          <div class="video-thumb">
            <img src="https://i.ytimg.com/vi/<?= e($v['youtube_id']) ?>/hqdefault.jpg" alt="" loading="lazy">
            <span class="video-play"><span><?= icon('play') ?></span></span>
            <?php if ($v['duration']): ?><span class="video-duration"><?= e($v['duration']) ?></span><?php endif; ?>
          </div>
          <div class="card-body">
            <h3 class="card-title"><?= e($v['title']) ?></h3>
            <p class="card-text"><?= e(excerpt($v['description'], 90)) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
