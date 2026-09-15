<div class="admin-toolbar">
  <?php foreach (['all' => 'All', 'pending' => 'Pending', 'awaiting_payment' => 'Awaiting payment', 'approved' => 'Approved', 'rejected' => 'Rejected', 'expired' => 'Expired'] as $k => $lbl): ?>
    <a class="filter-chip <?= $status === $k ? 'is-active' : '' ?>"
       href="<?= url('admin/members') . (PRETTY_URLS ? '?' : '&') . 'status=' . $k ?>">
      <?= e($lbl) ?> <span class="count"><?= (int) ($counts[$k] ?? 0) ?></span>
    </a>
  <?php endforeach; ?>

  <form method="get" style="margin-left:auto">
    <?php if (!PRETTY_URLS): ?><input type="hidden" name="p" value="admin/members"><?php endif; ?>
    <input type="hidden" name="status" value="<?= e($status) ?>">
    <div class="input-group">
      <?= icon('search') ?>
      <input class="input" type="search" name="q" value="<?= e($search) ?>" placeholder="Name, student ID or email…">
    </div>
    <button class="btn" type="submit">Search</button>
  </form>
</div>

<?php if (!$members): ?>
  <?php emptyState('No members match', 'Try another status filter or clear the search.', 'users'); ?>
<?php else: ?>
  <?php foreach ($members as $m):
      $photo = upload($m['photo'], 'members');
      $interests = array_filter(array_map('trim', explode(',', (string) $m['interests']))); ?>
    <div class="review-card">
      <div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.8rem">
          <?php if ($photo !== ''): ?>
            <img class="thumb" style="width:56px;height:56px;border-radius:50%" src="<?= e($photo) ?>" alt="">
          <?php else: ?>
            <span style="display:grid;place-items:center;width:56px;height:56px;border-radius:50%;color:#fff;font-weight:700;flex:none;<?= e(gradientFor($m['full_name'])) ?>"><?= e(initials($m['full_name'])) ?></span>
          <?php endif; ?>
          <div>
            <h3 style="font-size:var(--step-1)"><?= e($m['full_name']) ?></h3>
            <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.3rem">
              <span class="badge <?= e(statusBadge($m['status'])) ?>"><?= e(statusLabel($m['status'])) ?></span>
              <?php if ($m['member_no']): ?><span class="badge mono"><?= e($m['member_no']) ?></span><?php endif; ?>
              <?php if ($m['plan_name']): ?><span class="badge badge-brand"><?= e($m['plan_name']) ?></span><?php endif; ?>
            </div>
          </div>
        </div>

        <dl class="review-grid">
          <div><dt>Student ID</dt><dd><?= e($m['student_id']) ?></dd></div>
          <div><dt>Department</dt><dd><?= e($m['department']) ?> · <?= e($m['series']) ?></dd></div>
          <div><dt>Email</dt><dd><?= e($m['email']) ?></dd></div>
          <div><dt>Mobile</dt><dd class="mono"><?= e($m['phone']) ?></dd></div>
          <?php if ($m['semester']): ?><div><dt>Semester</dt><dd><?= e($m['semester']) ?></dd></div><?php endif; ?>
          <?php if ($m['blood_group']): ?><div><dt>Blood group</dt><dd><?= e($m['blood_group']) ?></dd></div><?php endif; ?>
          <div><dt>Applied</dt><dd><?= e(fdate($m['applied_at'])) ?></dd></div>
          <?php if ($m['expires_on']): ?><div><dt>Valid until</dt><dd><?= e(fdate($m['expires_on'])) ?></dd></div><?php endif; ?>
        </dl>

        <?php if ($interests): ?>
          <div class="tag-cloud" style="margin-top:.9rem">
            <?php foreach ($interests as $i): ?><span class="badge"><?= e($i) ?></span><?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($m['motivation']): ?>
          <p class="soft" style="margin-top:.9rem;font-size:var(--step-0)"><b>Why they applied:</b> <?= e($m['motivation']) ?></p>
        <?php endif; ?>
      </div>

      <div class="review-side">
        <form method="post" action="<?= url('admin/members/update/' . $m['id']) ?>" data-confirm="Update this member's status?">
          <?= csrfField() ?>
          <div class="field">
            <label for="ms-<?= (int) $m['id'] ?>">Status</label>
            <select class="select" id="ms-<?= (int) $m['id'] ?>" name="status">
              <?php foreach (['pending' => 'Pending', 'awaiting_payment' => 'Awaiting payment', 'approved' => 'Approved', 'rejected' => 'Rejected', 'expired' => 'Expired'] as $k => $lbl): ?>
                <option value="<?= e($k) ?>" <?= $m['status'] === $k ? 'selected' : '' ?>><?= e($lbl) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="mn-<?= (int) $m['id'] ?>">Note to applicant</label>
            <input class="input" type="text" id="mn-<?= (int) $m['id'] ?>" name="admin_note"
                   value="<?= e($m['admin_note']) ?>" maxlength="255" placeholder="Optional">
          </div>
          <button class="btn btn-block btn-sm" type="submit"><?= icon('check') ?> Save</button>
        </form>

        <form method="post" action="<?= url('admin/members/delete/' . $m['id']) ?>"
              data-confirm="Delete this application permanently? This also deletes its payment records.">
          <?= csrfField() ?>
          <button class="btn btn-ghost btn-block btn-sm" type="submit" style="color:var(--rose-500)">
            <?= icon('trash') ?> Delete application
          </button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
