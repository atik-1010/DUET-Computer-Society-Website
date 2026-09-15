<?php
$p = $post;
pageHero($p['title'], '', ['Blog' => 'blog', $p['category'] => null]);
$cover = upload($p['cover'], 'blog');
?>

<section class="section">
  <div class="container">
    <div class="with-sidebar">
      <article>
        <div class="reveal" style="border-radius:var(--radius-xl);overflow:hidden;aspect-ratio:16/8;margin-bottom:2rem;box-shadow:var(--shadow-lg)">
          <?php if ($cover !== ''): ?>
            <img src="<?= e($cover) ?>" alt="" style="width:100%;height:100%;object-fit:cover">
          <?php else: ?>
            <div style="width:100%;height:100%;display:grid;place-items:center;font-family:var(--font-display);font-size:clamp(2rem,7vw,4rem);font-weight:800;color:rgba(255,255,255,.9);<?= e(gradientFor($p['title'])) ?>"><?= e(initials($p['title'])) ?></div>
          <?php endif; ?>
        </div>

        <div class="meta-bar reveal">
          <div style="align-items:center">
            <span style="display:grid;place-items:center;width:38px;height:38px;border-radius:50%;color:#fff;font-size:.8rem;font-weight:700;flex:none;<?= e(gradientFor($p['author_name'] ?: 'DUETCS')) ?>"><?= e(initials($p['author_name'] ?: 'DUETCS')) ?></span>
            <span><small>Written by</small><b><?= e($p['author_name'] ?: 'DUETCS') ?></b></span>
          </div>
          <div><?= icon('calendar') ?><span><small>Published</small><b><?= e(fdate($p['published_at'])) ?></b></span></div>
          <div><?= icon('clock') ?><span><small>Reading time</small><b><?= (int) $p['read_minutes'] ?> minutes</b></span></div>
          <div><?= icon('eye') ?><span><small>Views</small><b><?= number_format((int) $p['views']) ?></b></span></div>
        </div>

        <div class="prose reveal"><?= $p['content'] ?></div>

        <?php if ($tags): ?>
          <div class="tag-cloud reveal" style="margin-top:2.5rem">
            <?php foreach ($tags as $t): ?><span class="badge">#<?= e($t) ?></span><?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="reveal" style="margin-top:2.5rem;padding-top:2rem;border-top:1px solid var(--border)">
          <a class="link" href="<?= url('blog') ?>"><?= icon('arrowLeft') ?> Back to all articles</a>
        </div>
      </article>

      <aside class="sidebar">
        <div class="widget">
          <h3>Share this</h3>
          <div class="social-row" style="--x:0">
            <?php $share = urlencode(BASE_URL . '/' . (PRETTY_URLS ? 'blog/' . $p['slug'] : 'index.php?p=blog/' . $p['slug'])); ?>
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)"
               href="https://www.facebook.com/sharer/sharer.php?u=<?= $share ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><?= icon('facebook') ?></a>
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)"
               href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $share ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><?= icon('linkedin') ?></a>
            <a style="background:var(--surface-2);border-color:var(--border);color:var(--text-soft)"
               href="mailto:?subject=<?= rawurlencode($p['title']) ?>&body=<?= $share ?>" aria-label="Share by email"><?= icon('mail') ?></a>
          </div>
        </div>

        <?php if ($related): ?>
          <div class="widget">
            <h3>More in <?= e($p['category']) ?></h3>
            <ul class="widget-list">
              <?php foreach ($related as $r): ?>
                <li><a href="<?= url('blog/' . $r['slug']) ?>"><?= e($r['title']) ?></a><small><?= e(fdate($r['published_at'])) ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ($popular): ?>
          <div class="widget">
            <h3>Most read</h3>
            <ul class="widget-list">
              <?php foreach ($popular as $r): ?>
                <li><a href="<?= url('blog/' . $r['slug']) ?>"><?= e($r['title']) ?></a><small><?= e(fdate($r['published_at'])) ?></small></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>
