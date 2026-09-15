<?php
/**
 * Reusable render functions for the public site.
 * Every one of these echoes escaped output.
 */

/**
 * Card media: a real image when one exists, otherwise a deterministic
 * gradient bearing the item's initials so no two cards look alike.
 */
function mediaBox(?string $file, string $folder, string $seed, string $class = 'card-media'): void
{
    $src = upload($file, $folder);
    echo '<div class="' . e($class) . '">';
    if ($src !== '') {
        echo '<img src="' . e($src) . '" alt="" loading="lazy">';
    } else {
        echo '<div class="ph" style="' . e(gradientFor($seed)) . '">' . e(initials($seed)) . '</div>';
    }
    echo '</div>';
}

/** Page header with breadcrumbs. $crumbs = [label => url|null]. */
function pageHero(string $title, string $subtitle = '', array $crumbs = []): void
{
    ?>
    <section class="page-hero">
      <div class="container">
        <?php if ($crumbs): ?>
          <nav class="crumbs" aria-label="Breadcrumb">
            <a href="<?= url() ?>">Home</a>
            <?php $last = array_key_last($crumbs); foreach ($crumbs as $label => $href): ?>
              <span class="sep">/</span>
              <?php if ($href && $label !== $last): ?>
                <a href="<?= url($href) ?>"><?= e($label) ?></a>
              <?php else: ?>
                <span aria-current="page"><?= e($label) ?></span>
              <?php endif; ?>
            <?php endforeach; ?>
          </nav>
        <?php endif; ?>
        <h1><?= e($title) ?></h1>
        <?php if ($subtitle !== ''): ?><p><?= e($subtitle) ?></p><?php endif; ?>
      </div>
    </section>
    <?php
}

/** Empty-state block. */
function emptyState(string $title, string $text = '', string $iconName = 'search'): void
{
    ?>
    <div class="empty">
      <?= icon($iconName) ?>
      <h3><?= e($title) ?></h3>
      <?php if ($text !== ''): ?><p><?= e($text) ?></p><?php endif; ?>
    </div>
    <?php
}

/** Numbered pagination. $link is a callable taking a page number. */
function paginate(int $page, int $pages, callable $link): void
{
    if ($pages < 2) {
        return;
    }
    ?>
    <nav class="pagination" aria-label="Pagination">
      <a class="<?= $page <= 1 ? 'is-disabled' : '' ?>" href="<?= e($link(max(1, $page - 1))) ?>" aria-label="Previous page">&larr;</a>
      <?php
      $from = max(1, $page - 2);
      $to   = min($pages, $page + 2);
      if ($from > 1) {
          echo '<a href="' . e($link(1)) . '">1</a>';
          if ($from > 2) echo '<span class="is-disabled">…</span>';
      }
      for ($i = $from; $i <= $to; $i++) {
          if ($i === $page) {
              echo '<span class="is-active" aria-current="page">' . $i . '</span>';
          } else {
              echo '<a href="' . e($link($i)) . '">' . $i . '</a>';
          }
      }
      if ($to < $pages) {
          if ($to < $pages - 1) echo '<span class="is-disabled">…</span>';
          echo '<a href="' . e($link($pages)) . '">' . $pages . '</a>';
      }
      ?>
      <a class="<?= $page >= $pages ? 'is-disabled' : '' ?>" href="<?= e($link(min($pages, $page + 1))) ?>" aria-label="Next page">&rarr;</a>
    </nav>
    <?php
}

/** Status → badge class for events and competitions. */
function statusBadge(string $status): string
{
    return match ($status) {
        'upcoming'  => 'badge-brand',
        'ongoing'   => 'badge-success badge-dot badge-live',
        'past'      => 'badge',
        'cancelled' => 'badge-danger',
        'verified', 'approved'  => 'badge-success',
        'pending', 'awaiting_payment' => 'badge-warning',
        'rejected', 'expired'   => 'badge-danger',
        default     => 'badge',
    };
}

/** Human label for a status value. */
function statusLabel(string $status): string
{
    return match ($status) {
        'awaiting_payment' => 'Awaiting payment',
        default => ucfirst(str_replace('_', ' ', $status)),
    };
}

/** Event card. */
function eventCard(array $ev): void
{
    ?>
    <article class="card reveal">
      <?php mediaBox($ev['banner'] ?? null, 'events', $ev['title']); ?>
      <div class="card-body">
        <div class="card-meta">
          <span class="badge <?= e(statusBadge($ev['status'])) ?>"><?= e(statusLabel($ev['status'])) ?></span>
          <?php if (!empty($ev['category'])): ?><span class="badge"><?= e($ev['category']) ?></span><?php endif; ?>
        </div>
        <h3 class="card-title"><a href="<?= url('event/' . $ev['slug']) ?>"><?= e($ev['title']) ?></a></h3>
        <p class="card-text"><?= e(excerpt($ev['summary'] ?? $ev['content'] ?? '', 130)) ?></p>
        <div class="card-meta">
          <span><?= icon('calendar') ?><?= e(fdate($ev['start_datetime'])) ?></span>
          <?php if (!empty($ev['venue'])): ?><span><?= icon('pin') ?><?= e(excerpt($ev['venue'], 34)) ?></span><?php endif; ?>
        </div>
      </div>
      <div class="card-foot">
        <span><?= icon('clock') ?> <?= e(date('g:i A', strtotime($ev['start_datetime']))) ?></span>
        <a class="link" href="<?= url('event/' . $ev['slug']) ?>">Details <?= icon('arrow') ?></a>
      </div>
    </article>
    <?php
}

/** Blog post card. */
function postCard(array $p): void
{
    ?>
    <article class="card reveal">
      <?php mediaBox($p['cover'] ?? null, 'blog', $p['title']); ?>
      <div class="card-body">
        <div class="card-meta"><span class="badge badge-brand"><?= e($p['category']) ?></span></div>
        <h3 class="card-title"><a href="<?= url('blog/' . $p['slug']) ?>"><?= e($p['title']) ?></a></h3>
        <p class="card-text"><?= e(excerpt($p['excerpt'] ?? $p['content'] ?? '', 140)) ?></p>
      </div>
      <div class="card-foot">
        <span><?= icon('users') ?> <?= e($p['author_name'] ?: 'DUETCS') ?></span>
        <span><?= icon('clock') ?> <?= (int) $p['read_minutes'] ?> min read</span>
      </div>
    </article>
    <?php
}

/** Project card. */
function projectCard(array $pr): void
{
    $stack = array_filter(array_map('trim', explode(',', (string) $pr['tech_stack'])));
    ?>
    <article class="card reveal">
      <?php mediaBox($pr['image'] ?? null, 'projects', $pr['title']); ?>
      <div class="card-body">
        <div class="card-meta">
          <span class="badge <?= $pr['status'] === 'ongoing' ? 'badge-success' : 'badge-brand' ?>"><?= e(ucfirst($pr['status'])) ?></span>
          <?php if (!empty($pr['year'])): ?><span class="badge"><?= e($pr['year']) ?></span><?php endif; ?>
        </div>
        <h3 class="card-title"><a href="<?= url('project/' . $pr['slug']) ?>"><?= e($pr['title']) ?></a></h3>
        <p class="card-text"><?= e(excerpt($pr['summary'] ?? '', 130)) ?></p>
        <?php if ($stack): ?>
          <div class="tag-cloud">
            <?php foreach (array_slice($stack, 0, 4) as $t): ?><span class="badge"><?= e($t) ?></span><?php endforeach; ?>
            <?php if (count($stack) > 4): ?><span class="badge">+<?= count($stack) - 4 ?></span><?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="card-foot">
        <?php if (!empty($pr['github_url'])): ?>
          <a class="link" href="<?= e($pr['github_url']) ?>" target="_blank" rel="noopener"><?= icon('github') ?> Source</a>
        <?php else: ?><span></span><?php endif; ?>
        <a class="link" href="<?= url('project/' . $pr['slug']) ?>">Case study <?= icon('arrow') ?></a>
      </div>
    </article>
    <?php
}

/** Competition card. */
function competitionCard(array $c): void
{
    ?>
    <article class="card reveal">
      <?php mediaBox($c['banner'] ?? null, 'events', $c['title']); ?>
      <div class="card-body">
        <div class="card-meta">
          <span class="badge <?= e(statusBadge($c['status'])) ?>"><?= e(statusLabel($c['status'])) ?></span>
          <span class="badge badge-violet"><?= e(strtoupper($c['type'])) ?></span>
        </div>
        <h3 class="card-title"><a href="<?= url('competition/' . $c['slug']) ?>"><?= e($c['title']) ?></a></h3>
        <p class="card-text"><?= e(excerpt($c['summary'] ?? '', 130)) ?></p>
        <div class="card-meta">
          <?php if (!empty($c['event_date'])): ?><span><?= icon('calendar') ?><?= e(fdate($c['event_date'])) ?></span><?php endif; ?>
          <?php if (!empty($c['participants'])): ?><span><?= icon('users') ?><?= (int) $c['participants'] ?> participants</span><?php endif; ?>
        </div>
      </div>
      <div class="card-foot">
        <?php if (!empty($c['champion'])): ?>
          <span><?= icon('trophy') ?> <?= e(excerpt($c['champion'], 26)) ?></span>
        <?php elseif (!empty($c['prize_pool'])): ?>
          <span><?= icon('award') ?> <?= e($c['prize_pool']) ?></span>
        <?php else: ?><span></span><?php endif; ?>
        <a class="link" href="<?= url('competition/' . $c['slug']) ?>">Details <?= icon('arrow') ?></a>
      </div>
    </article>
    <?php
}

/** Activity card. */
function activityCard(array $a): void
{
    ?>
    <article class="card reveal">
      <?php mediaBox($a['image'] ?? null, 'events', $a['title']); ?>
      <div class="card-body">
        <div class="card-meta">
          <span class="badge badge-accent"><?= e(ucfirst($a['level'])) ?></span>
          <?php if (!empty($a['duration'])): ?><span class="badge"><?= e($a['duration']) ?></span><?php endif; ?>
        </div>
        <h3 class="card-title"><a href="<?= url('activity/' . $a['slug']) ?>"><?= e($a['title']) ?></a></h3>
        <p class="card-text"><?= e(excerpt($a['summary'] ?? '', 135)) ?></p>
      </div>
      <div class="card-foot">
        <span><?= icon('users') ?> <?= e($a['instructor'] ?: 'DUETCS') ?></span>
        <a class="link" href="<?= url('activity/' . $a['slug']) ?>">Read more <?= icon('arrow') ?></a>
      </div>
    </article>
    <?php
}

/** Person card used for committee members and advisors. */
function personCard(array $m): void
{
    $photo = upload($m['photo'] ?? null, 'committee');
    ?>
    <article class="person reveal">
      <div class="person-photo">
        <?php if ($photo !== ''): ?>
          <img src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>" loading="lazy">
        <?php else: ?>
          <div class="ph" style="<?= e(gradientFor($m['name'])) ?>"><?= e(initials($m['name'])) ?></div>
        <?php endif; ?>
        <div class="person-social">
          <?php if (!empty($m['facebook'])): ?><a href="<?= e($m['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook profile of <?= e($m['name']) ?>"><?= icon('facebook') ?></a><?php endif; ?>
          <?php if (!empty($m['linkedin'])): ?><a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn profile of <?= e($m['name']) ?>"><?= icon('linkedin') ?></a><?php endif; ?>
          <?php if (!empty($m['github'])): ?><a href="<?= e($m['github']) ?>" target="_blank" rel="noopener" aria-label="GitHub profile of <?= e($m['name']) ?>"><?= icon('github') ?></a><?php endif; ?>
          <?php if (!empty($m['email'])): ?><a href="mailto:<?= e($m['email']) ?>" aria-label="Email <?= e($m['name']) ?>"><?= icon('mail') ?></a><?php endif; ?>
        </div>
      </div>
      <div class="person-info">
        <h3><?= e($m['name']) ?></h3>
        <span class="person-role"><?= e($m['designation']) ?></span>
        <?php if (!empty($m['department'])): ?>
          <span class="person-dept"><?= e($m['department']) ?><?= !empty($m['series']) ? ' · ' . e($m['series']) : '' ?></span>
        <?php endif; ?>
      </div>
    </article>
    <?php
}
