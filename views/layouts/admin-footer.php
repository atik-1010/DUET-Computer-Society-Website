    </div>
  </div>
</div>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer">
  <button class="lightbox-close icon-btn" type="button" aria-label="Close"><?= icon('close') ?></button>
  <div>
    <img id="lightbox-img" src="" alt="">
    <p class="lightbox-cap" id="lightbox-cap"></p>
  </div>
</div>

<script src="<?= asset('js/main.js') ?>" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var side = document.getElementById('admin-side');
  var btn  = document.querySelector('.admin-side-toggle');
  if (btn && side) {
    btn.addEventListener('click', function () { side.classList.toggle('is-open'); });
  }

  // Every destructive action asks first.
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!window.confirm(f.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  // Auto-fill the slug from the title while typing, until the user edits it.
  var slug = document.querySelector('[data-slug-target]');
  if (slug && !slug.value) {
    var src = document.getElementById(slug.getAttribute('data-slug-target'));
    if (src) {
      var touched = false;
      slug.addEventListener('input', function () { touched = true; });
      src.addEventListener('input', function () {
        if (touched) return;
        slug.value = src.value.toLowerCase().trim()
          .replace(/[^a-z0-9\s-]/g, '')
          .replace(/\s+/g, '-')
          .replace(/-+/g, '-');
      });
    }
  }
});
</script>
</body>
</html>
