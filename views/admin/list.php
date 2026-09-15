<?php
/** Generic list view for any resource declared in admin_resources.php. */
$imageField = null;
foreach ($res['fields'] as $name => $def) {
    if ($def[0] === 'image') { $imageField = $name; break; }
}
$folder = $res['upload'] ?? '';
$base   = url('admin/' . $section) . (PRETTY_URLS ? '?' : '&');
?>

<div class="admin-toolbar">
  <a class="btn" href="<?= url('admin/' . $section . '/create') ?>"><?= icon('plus') ?> New <?= e(strtolower($res['singular'])) ?></a>
  <span class="badge"><?= number_format($total) ?> record<?= $total === 1 ? '' : 's' ?></span>

  <?php if (!empty($res['search'])): ?>
    <form method="get" style="margin-left:auto">
      <?php if (!PRETTY_URLS): ?><input type="hidden" name="p" value="admin/<?= e($section) ?>"><?php endif; ?>
      <div class="input-group">
        <?= icon('search') ?>
        <input class="input" type="search" name="q" value="<?= e($search) ?>" placeholder="Search <?= e(strtolower($res['label'])) ?>…">
      </div>
      <button class="btn" type="submit">Search</button>
      <?php if ($search !== ''): ?>
        <a class="btn btn-ghost" href="<?= url('admin/' . $section) ?>">Clear</a>
      <?php endif; ?>
    </form>
  <?php endif; ?>
</div>

<?php if (!$rows): ?>
  <?php emptyState('Nothing here yet', $search !== '' ? 'No records match “' . $search . '”.' : 'Create the first ' . strtolower($res['singular']) . ' to get started.', $res['icon']); ?>
<?php else: ?>
  <div class="panel">
    <div class="table-wrap" style="border:0;border-radius:0">
      <table class="table">
        <thead>
          <tr>
            <?php if ($imageField): ?><th style="width:64px"></th><?php endif; ?>
            <?php foreach ($res['list'] as $col => $label): ?><th><?= e($label) ?></th><?php endforeach; ?>
            <th style="width:110px"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <?php if ($imageField):
                  $img = upload($row[$imageField] ?? null, $folder);
                  $seed = (string) ($row['title'] ?? $row['name'] ?? $row['label'] ?? $row['id']); ?>
                <td>
                  <?php if ($img !== ''): ?>
                    <img class="thumb" src="<?= e($img) ?>" alt="" loading="lazy">
                  <?php else: ?>
                    <span class="thumb" style="display:grid;place-items:center;color:#fff;font-size:.7rem;font-weight:700;<?= e(gradientFor($seed)) ?>"><?= e(initials($seed)) ?></span>
                  <?php endif; ?>
                </td>
              <?php endif; ?>

              <?php foreach ($res['list'] as $col => $label):
                  $value = $row[$col] ?? '';
                  $type  = $res['fields'][$col][0] ?? 'text'; ?>
                <td>
                  <?php
                  if (isset($lookups[$col])) {
                      echo '<span class="badge">' . e($lookups[$col][$value] ?? $value) . '</span>';
                  } elseif ($type === 'checkbox') {
                      echo $value
                          ? '<span class="badge badge-success">' . icon('check') . ' Yes</span>'
                          : '<span class="badge">No</span>';
                  } elseif (in_array($col, ['status'], true)) {
                      echo '<span class="badge ' . e(statusBadge((string) $value)) . '">' . e(statusLabel((string) $value)) . '</span>';
                  } elseif ($type === 'date' || $type === 'datetime') {
                      echo '<span class="muted">' . e(fdate((string) $value, $type === 'datetime' ? 'd M Y, g:i A' : 'd M Y')) . '</span>';
                  } elseif ($type === 'money') {
                      echo '<b>' . e(taka($value)) . '</b>';
                  } elseif ($col === 'title' || $col === 'name' || $col === 'label') {
                      echo '<b>' . e(excerpt((string) $value, 60)) . '</b>';
                      if (!empty($row['slug'])) {
                          echo '<div class="muted mono" style="font-size:var(--step--1)">' . e($row['slug']) . '</div>';
                      }
                  } else {
                      echo e(excerpt((string) $value, 46));
                  }
                  ?>
                </td>
              <?php endforeach; ?>

              <td>
                <div class="row-actions">
                  <a class="icon-btn" href="<?= url('admin/' . $section . '/edit/' . $row['id']) ?>" aria-label="Edit" title="Edit"><?= icon('edit') ?></a>
                  <form method="post" action="<?= url('admin/' . $section . '/delete/' . $row['id']) ?>"
                        data-confirm="Delete this <?= e(strtolower($res['singular'])) ?> permanently?">
                    <?= csrfField() ?>
                    <button class="icon-btn danger" type="submit" aria-label="Delete" title="Delete"><?= icon('trash') ?></button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php paginate($pageNo, $pages, fn($n) => $base . 'page=' . $n . ($search !== '' ? '&q=' . urlencode($search) : '')); ?>
<?php endif; ?>
