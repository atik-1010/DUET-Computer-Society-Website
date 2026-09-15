<form method="post" class="admin-form">
  <?= csrfField() ?>

  <?php
  $groups = [
      'Contact details' => [
          'site_email'   => ['Public email address', 'email'],
          'site_phone'   => ['Public phone number', 'text'],
          'site_address' => ['Postal address', 'textarea'],
          'map_embed'    => ['Google Maps embed URL', 'text'],
      ],
      'Social links' => [
          'facebook_url' => ['Facebook page URL', 'text'],
          'github_url'   => ['GitHub organisation URL', 'text'],
          'linkedin_url' => ['LinkedIn page URL', 'text'],
          'youtube_url'  => ['YouTube channel URL', 'text'],
      ],
      'Home page statistics' => [
          'stat_members'  => ['Active members', 'number'],
          'stat_events'   => ['Events delivered', 'number'],
          'stat_contests' => ['Contests hosted', 'number'],
          'stat_years'    => ['Years running', 'number'],
      ],
      'Membership' => [
          'payment_note' => ['Note shown on the payment form', 'textarea'],
      ],
  ];

  foreach ($groups as $group => $items): ?>
    <div class="panel">
      <div class="panel-head"><h2><?= e($group) ?></h2></div>
      <div class="panel-body">
        <?php foreach ($items as $key => [$label, $type]): ?>
          <div class="field">
            <label for="s-<?= e($key) ?>"><?= e($label) ?></label>
            <?php if ($type === 'textarea'): ?>
              <textarea class="textarea" id="s-<?= e($key) ?>" name="settings[<?= e($key) ?>]" rows="3"><?= e($settings[$key] ?? '') ?></textarea>
            <?php else: ?>
              <input class="input" type="<?= e($type) ?>" id="s-<?= e($key) ?>" name="settings[<?= e($key) ?>]"
                     value="<?= e($settings[$key] ?? '') ?>">
            <?php endif; ?>
            <span class="field-hint mono"><?= e($key) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="alert alert-info">
    <?= icon('settings') ?>
    <div>
      Database credentials, the site name and the base URL live in
      <code>config/config.php</code>. Editing that file is a deliberate step — it is not exposed here.
    </div>
  </div>

  <div class="form-actions">
    <button class="btn btn-lg" type="submit"><?= icon('check') ?> Save settings</button>
    <a class="btn btn-ghost btn-lg" href="<?= url('admin') ?>">Cancel</a>
  </div>
</form>
