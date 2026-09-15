<?php
/** Generic create/edit form built from the resource field definitions. */
$folder = $res['upload'] ?? '';
?>

<div class="admin-toolbar">
  <a class="btn btn-ghost" href="<?= url('admin/' . $section) ?>"><?= icon('arrowLeft') ?> Back to <?= e(strtolower($res['label'])) ?></a>
</div>

<form class="admin-form" method="post" enctype="multipart/form-data" data-guard>
  <?= csrfField() ?>

  <div class="panel">
    <div class="panel-head">
      <h2><?= $editing ? 'Edit' : 'New' ?> <?= e(strtolower($res['singular'])) ?></h2>
      <?php if ($editing): ?><span class="badge mono">ID <?= (int) $recordId ?></span><?php endif; ?>
    </div>

    <div class="panel-body">
      <?php foreach ($fields as $name => $def):
          $type     = $def[0];
          $label    = $def[1];
          $rules    = $def[2] ?? '';
          $hint     = $def[3] ?? '';
          $required = str_contains($rules, 'required');
          $value    = $old[$name] ?? '';
          $invalid  = isset($errors[$name]) ? 'aria-invalid="true"' : '';
          $id       = 'f-' . $name;
      ?>
        <div class="field">
          <label for="<?= e($id) ?>">
            <?= e($label) ?><?= $required ? ' <span class="req">*</span>' : '' ?>
          </label>

          <?php if ($type === 'textarea'): ?>
            <textarea class="textarea" id="<?= e($id) ?>" name="<?= e($name) ?>" rows="4" <?= $invalid ?>><?= e($value) ?></textarea>

          <?php elseif ($type === 'html'): ?>
            <textarea class="textarea mono" id="<?= e($id) ?>" name="<?= e($name) ?>" rows="14"
                      style="font-size:var(--step--1);line-height:1.7" <?= $invalid ?>><?= e($value) ?></textarea>
            <span class="field-hint">
              HTML is allowed here: <code>&lt;p&gt;</code>, <code>&lt;h3&gt;</code>, <code>&lt;strong&gt;</code>,
              <code>&lt;ul&gt;&lt;li&gt;</code>, <code>&lt;a href&gt;</code>, <code>&lt;pre&gt;&lt;code&gt;</code>.
            </span>

          <?php elseif ($type === 'select'):
              $opts = fieldOptions($def); ?>
            <select class="select" id="<?= e($id) ?>" name="<?= e($name) ?>" <?= $required ? 'required' : '' ?> <?= $invalid ?>>
              <?php if (!$required): ?><option value="">— none —</option><?php endif; ?>
              <?php foreach ($opts as $k => $lbl): ?>
                <option value="<?= e($k) ?>" <?= (string) $value === (string) $k ? 'selected' : '' ?>><?= e($lbl) ?></option>
              <?php endforeach; ?>
            </select>

          <?php elseif ($type === 'checkbox'): ?>
            <label class="check">
              <input type="checkbox" id="<?= e($id) ?>" name="<?= e($name) ?>" value="1" <?= $value ? 'checked' : '' ?>>
              <span>Yes</span>
            </label>

          <?php elseif ($type === 'image'):
              $current = upload($value, $folder); ?>
            <?php if ($current !== ''): ?>
              <div class="current-image">
                <img src="<?= e($current) ?>" alt="">
                <span>Current file — uploading a new one replaces it.</span>
              </div>
            <?php endif; ?>
            <label class="file-drop">
              <input type="file" id="<?= e($id) ?>" name="<?= e($name) ?>" accept="image/jpeg,image/png,image/webp,image/gif">
              <?= icon('image') ?>
              <span><strong>Click to upload</strong> or drag an image here</span>
              <span class="field-hint file-name">JPG, PNG, WEBP or GIF · up to 3 MB</span>
              <img class="file-preview" hidden alt="" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-height:120px;border-radius:10px;margin-top:.6rem">
            </label>

          <?php elseif ($type === 'slug'):
              $src = '';
              if (preg_match('/from:(\w+)/', $rules, $mm)) { $src = 'f-' . $mm[1]; } ?>
            <input class="input mono" type="text" id="<?= e($id) ?>" name="<?= e($name) ?>"
                   value="<?= e($value) ?>" data-slug-target="<?= e($src) ?>" <?= $invalid ?>>
            <span class="field-hint">Leave blank to generate it automatically. Must be unique.</span>

          <?php elseif ($type === 'datetime'): ?>
            <input class="input" type="datetime-local" id="<?= e($id) ?>" name="<?= e($name) ?>"
                   value="<?= e($value ? date('Y-m-d\TH:i', strtotime((string) $value)) : '') ?>"
                   <?= $required ? 'required' : '' ?> <?= $invalid ?>>

          <?php elseif ($type === 'date'): ?>
            <input class="input" type="date" id="<?= e($id) ?>" name="<?= e($name) ?>"
                   value="<?= e($value ? date('Y-m-d', strtotime((string) $value)) : '') ?>"
                   <?= $required ? 'required' : '' ?> <?= $invalid ?>>

          <?php else:
              $inputType = match ($type) {
                  'number', 'money' => 'number',
                  'email' => 'email',
                  'url'   => 'url',
                  default => 'text',
              }; ?>
            <input class="input" type="<?= e($inputType) ?>" id="<?= e($id) ?>" name="<?= e($name) ?>"
                   value="<?= e($value) ?>" <?= $required ? 'required' : '' ?>
                   <?= $type === 'money' ? 'step="0.01" min="0"' : '' ?>
                   <?= $type === 'number' ? 'step="1"' : '' ?> <?= $invalid ?>>
          <?php endif; ?>

          <?php if ($hint && $type !== 'html' && $type !== 'slug' && $type !== 'image'): ?>
            <span class="field-hint"><?= e($hint) ?></span>
          <?php endif; ?>
          <?php if (isset($errors[$name])): ?>
            <span class="field-error"><?= e($errors[$name]) ?></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="form-actions">
    <button class="btn btn-lg" type="submit"><?= icon('check') ?> <?= $editing ? 'Save changes' : 'Create ' . e(strtolower($res['singular'])) ?></button>
    <a class="btn btn-ghost btn-lg" href="<?= url('admin/' . $section) ?>">Cancel</a>
  </div>
</form>
