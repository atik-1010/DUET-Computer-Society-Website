<section class="section" style="min-height:60vh;display:grid;place-items:center">
  <div class="container center" style="max-width:38rem">
    <div style="font-family:var(--font-display);font-size:clamp(5rem,18vw,10rem);font-weight:800;line-height:1;background:var(--gradient-text);-webkit-background-clip:text;background-clip:text;color:transparent">404</div>
    <h1 style="font-size:var(--step-3);margin-block:1rem .8rem">This page does not exist</h1>
    <p class="soft" style="margin-bottom:2rem">
      The link may be outdated, or the page may have moved. Try the search below, or head back to the home page.
    </p>
    <form action="<?= url('search') ?>" method="get" style="display:flex;gap:.6rem;max-width:24rem;margin:0 auto 2rem">
      <?php if (!PRETTY_URLS): ?><input type="hidden" name="p" value="search"><?php endif; ?>
      <div class="input-group" style="flex:1">
        <?= icon('search') ?>
        <input class="input" type="search" name="q" placeholder="Search the site…" aria-label="Search">
      </div>
      <button class="btn" type="submit">Search</button>
    </form>
    <div class="btn-group" style="justify-content:center">
      <a class="btn btn-ghost" href="<?= url() ?>"><?= icon('arrowLeft') ?> Back to home</a>
      <a class="btn btn-ghost" href="<?= url('contact') ?>"><?= icon('mail') ?> Report a broken link</a>
    </div>
  </div>
</section>
