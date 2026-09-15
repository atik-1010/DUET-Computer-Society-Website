<?php pageHero('Search', $term !== '' ? $count . ' result' . ($count === 1 ? '' : 's') . ' for “' . $term . '”' : 'Search across events, activities, competitions, projects and articles.', ['Search' => null]); ?>

<section class="section">
  <div class="container container-narrow">
    <form action="<?= url('search') ?>" method="get" class="reveal" style="display:flex;gap:.7rem;margin-bottom:2.5rem">
      <?php if (!PRETTY_URLS): ?><input type="hidden" name="p" value="search"><?php endif; ?>
      <div class="input-group" style="flex:1">
        <?= icon('search') ?>
        <input class="input" type="search" name="q" value="<?= e($term) ?>" placeholder="Search the site…" aria-label="Search" autofocus>
      </div>
      <button class="btn" type="submit">Search</button>
    </form>

    <?php if ($term === ''): ?>
      <?php emptyState('Type something to search', 'Try “IUPC”, “machine learning”, “SQL injection” or “membership”.', 'search'); ?>
    <?php elseif (!$results): ?>
      <?php emptyState('No results', 'Nothing matched “' . $term . '”. Try a shorter or more general term.', 'search'); ?>
    <?php else: ?>
      <?php foreach ($results as $group => $data): ?>
        <div style="margin-bottom:2.5rem">
          <h2 style="font-size:var(--step-2);margin-bottom:1rem"><?= e($group) ?> <span class="muted" style="font-weight:400">(<?= count($data['rows']) ?>)</span></h2>
          <div class="stack" style="--stack-gap:.8rem">
            <?php foreach ($data['rows'] as $r): ?>
              <a class="card reveal" href="<?= url($data['route'] . '/' . $r['slug']) ?>" style="padding:1.15rem 1.4rem">
                <h3 class="card-title" style="font-size:var(--step-1)"><?= e($r['title']) ?></h3>
                <?php if ($r['summary']): ?><p class="card-text" style="margin-top:.35rem"><?= e(excerpt($r['summary'], 150)) ?></p><?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
