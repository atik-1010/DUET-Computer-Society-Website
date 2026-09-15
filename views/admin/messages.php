<div class="grid" style="--grid-gap:1.5rem;grid-template-columns:minmax(0,1.6fr) minmax(0,1fr);align-items:start">
  <div>
    <div class="panel">
      <div class="panel-head">
        <h2><?= icon('mail') ?> Contact messages</h2>
        <span class="badge"><?= count($messages) ?> total</span>
      </div>
      <div class="panel-body">
        <?php if (!$messages): ?>
          <p class="muted">No messages yet.</p>
        <?php else: ?>
          <?php foreach ($messages as $m): ?>
            <div style="padding:1.1rem 1.2rem;border:1px solid var(--border);border-radius:var(--radius);margin-bottom:.9rem;<?= $m['is_read'] ? '' : 'border-left:3px solid var(--brand-500);background:var(--surface-2)' ?>">
              <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.6rem;margin-bottom:.5rem">
                <b style="font-size:var(--step-1)"><?= e($m['subject']) ?></b>
                <?php if (!$m['is_read']): ?><span class="badge badge-brand">Unread</span><?php endif; ?>
                <span class="muted" style="margin-left:auto;font-size:var(--step--1)"><?= e(fdate($m['created_at'], 'd M Y, g:i A')) ?></span>
              </div>

              <p class="muted" style="font-size:var(--step--1);margin-bottom:.7rem">
                <?= icon('users') ?> <?= e($m['name']) ?> ·
                <a class="link" href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a>
                <?php if ($m['phone']): ?> · <?= e($m['phone']) ?><?php endif; ?>
              </p>

              <p class="soft" style="white-space:pre-line"><?= e($m['message']) ?></p>

              <div class="btn-group" style="margin-top:1rem;gap:.5rem">
                <a class="btn btn-ghost btn-sm" href="mailto:<?= e($m['email']) ?>?subject=<?= rawurlencode('Re: ' . $m['subject']) ?>">
                  <?= icon('mail') ?> Reply
                </a>
                <?php if (!$m['is_read']): ?>
                  <form method="post" action="<?= url('admin/messages/read/' . $m['id']) ?>">
                    <?= csrfField() ?>
                    <button class="btn btn-ghost btn-sm" type="submit"><?= icon('check') ?> Mark read</button>
                  </form>
                <?php endif; ?>
                <form method="post" action="<?= url('admin/messages/delete/' . $m['id']) ?>" data-confirm="Delete this message?">
                  <?= csrfField() ?>
                  <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--rose-500)"><?= icon('trash') ?> Delete</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <h2><?= icon('users') ?> Newsletter</h2>
      <span class="badge"><?= count($subscribers) ?></span>
    </div>
    <div class="panel-body">
      <?php if (!$subscribers): ?>
        <p class="muted">No subscribers yet.</p>
      <?php else: ?>
        <p class="muted" style="font-size:var(--step--1);margin-bottom:1rem">
          Copy this list into your mail client's BCC field when sending an announcement.
        </p>
        <textarea class="textarea mono" rows="10" readonly style="font-size:var(--step--1)"><?= e(implode(', ', array_column($subscribers, 'email'))) ?></textarea>
      <?php endif; ?>
    </div>
  </div>
</div>
