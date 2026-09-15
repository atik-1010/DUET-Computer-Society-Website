<?php pageHero($title, $subtitle, ['Blog' => 'blog']);
$base = url('blog') . (PRETTY_URLS ? '?' : '&');
?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <div>
        <div class="filter-row reveal">
          <a class="filter-chip <?= $cat === 'all' ? 'is-active' : '' ?>" href="<?= url('blog') ?>">
            All <span class="count"><?= array_sum($cats) ?></span>
          </a>
          <?php foreach ($cats as $key => $n): ?>
            <a class="filter-chip <?= $cat === $key ? 'is-active' : '' ?>" href="<?= e($base . 'category=' . urlencode($key)) ?>">
              <?= e($key) ?> <span class="count"><?= $n ?></span>
            </a>
          <?php endforeach; ?>
        </div>

        <?php if (!$posts): ?>
          <?php emptyState('No articles here yet', 'Try another category.', 'book'); ?>
        <?php else: ?>
          <div class="grid grid-2">
            <?php foreach ($posts as $p) postCard($p); ?>
          </div>
          <?php paginate($pageNo, $pages, fn($n) => $base . 'page=' . $n . ($cat !== 'all' ? '&category=' . urlencode($cat) : '')); ?>
        <?php endif; ?>
      </div>

      <aside class="sidebar">
        <div class="widget">
          <h3>Search articles</h3>
          <form action="<?= url('search') ?>" method="get" style="display:flex;gap:.5rem">
            <?php if (!PRETTY_URLS): ?><input type="hidden" name="p" value="search"><?php endif; ?>
            <div class="input-group" style="flex:1">
              <?= icon('search') ?>
              <input class="input" type="search" name="q" placeholder="Search…" aria-label="Search articles">
            </div>
            <button class="btn" type="submit" aria-label="Search"><?= icon('arrow') ?></button>
          </form>
        </div>

        <?php if ($popular): ?>
          <div class="widget">
            <h3>Most read</h3>
            <ul class="widget-list">
              <?php foreach ($popular as $p): ?>
                <li><a href="<?= url('blog/' . $p['slug']) ?>"><?= e($p['title']) ?></a><small><?= e(fdate($p['published_at'])) ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="widget">
          <h3>Categories</h3>
          <div class="tag-cloud">
            <?php foreach ($cats as $key => $n): ?>
              <a href="<?= e($base . 'category=' . urlencode($key)) ?>"><?= e($key) ?> (<?= $n ?>)</a>
            <?php endforeach; ?>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>
