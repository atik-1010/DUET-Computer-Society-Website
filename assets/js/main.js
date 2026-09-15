/* =====================================================================
   DUETCS — site behaviour
   Progressive enhancement only: every page works with JS disabled.
   ===================================================================== */
(function () {
  'use strict';

  var $  = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

  /* ---------------------------------------------------------- theme */
  var root = document.documentElement;

  function currentTheme() {
    var stored = null;
    try { stored = localStorage.getItem('duetcs-theme'); } catch (e) { /* private mode */ }
    if (stored === 'light' || stored === 'dark') return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    root.setAttribute('data-theme', theme);
    try { localStorage.setItem('duetcs-theme', theme); } catch (e) { /* ignore */ }
    $$('.theme-toggle').forEach(function (btn) {
      btn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
      btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    });
  }

  $$('.theme-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    });
  });
  applyTheme(currentTheme());

  /* ------------------------------------------------- sticky header */
  var header = $('.site-header');
  var toTop  = $('.to-top');
  var progress = $('.progress-bar');

  function onScroll() {
    var y = window.scrollY || window.pageYOffset;
    if (header) header.classList.toggle('is-stuck', y > 8);
    if (toTop)  toTop.classList.toggle('is-visible', y > 550);
    if (progress) {
      var h = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (toTop) {
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ------------------------------------------------ mobile drawer */
  var drawer  = $('#drawer');
  var overlay = $('#overlay');

  function setDrawer(open) {
    if (!drawer) return;
    drawer.classList.toggle('is-open', open);
    if (overlay) overlay.classList.toggle('is-open', open);
    document.body.style.overflow = open ? 'hidden' : '';
    $$('.menu-toggle').forEach(function (b) { b.setAttribute('aria-expanded', open ? 'true' : 'false'); });
    if (open) {
      var first = drawer.querySelector('a, button');
      if (first) first.focus();
    }
  }

  $$('.menu-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () { setDrawer(!drawer.classList.contains('is-open')); });
  });
  if (overlay) overlay.addEventListener('click', function () { setDrawer(false); });
  $$('#drawer a').forEach(function (a) { a.addEventListener('click', function () { setDrawer(false); }); });

  // Collapsible groups inside the drawer
  $$('.drawer-group > button').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var group = btn.parentElement;
      var open  = group.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    setDrawer(false);
    closeLightbox();
    var vm = $('#video-modal');
    if (vm && vm.classList.contains('is-open')) closeVideo();
  });

  /* ------------------------------------------------ scroll reveal */
  var revealables = $$('.reveal');
  if ('IntersectionObserver' in window && revealables.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    revealables.forEach(function (el, i) {
      // Stagger siblings inside the same grid for a cascading effect.
      if (!el.style.getPropertyValue('--delay')) {
        el.style.setProperty('--delay', ((i % 4) * 80) + 'ms');
      }
      // Anything already on screen at load is shown at once, so the first
      // viewport never waits on a scroll event that may not come.
      if (el.getBoundingClientRect().top < window.innerHeight) {
        el.classList.add('is-visible');
      } else {
        io.observe(el);
      }
    });

    // Safety net: whatever the observer has not handled after a few seconds
    // is revealed anyway. Content must never be permanently invisible.
    setTimeout(function () {
      revealables.forEach(function (el) { el.classList.add('is-visible'); });
    }, 4000);
  } else {
    revealables.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* -------------------------------------------- animated counters */
  var counters = $$('[data-count]');
  if ('IntersectionObserver' in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        countUp(entry.target);
        cio.unobserve(entry.target);
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { cio.observe(el); });
  } else {
    counters.forEach(countUp);
  }
  // Counters in the first viewport run immediately rather than waiting.
  counters.forEach(function (el) {
    if (el.getBoundingClientRect().top < window.innerHeight) {
      cio && cio.unobserve(el);
      countUp(el);
    }
  });

  function countUp(el) {
    var target = parseFloat(el.getAttribute('data-count')) || 0;
    var suffix = el.getAttribute('data-suffix') || '';
    var dur = 1400;
    var start = performance.now();
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      el.textContent = target.toLocaleString() + suffix;
      return;
    }
    function tick(now) {
      var p = Math.min((now - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased).toLocaleString() + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  /* ------------------------------------------------------- toasts */
  $$('.toast').forEach(function (toast) {
    var close = $('.toast-close', toast);
    var hide = function () {
      toast.classList.add('is-out');
      setTimeout(function () { toast.remove(); }, 320);
    };
    if (close) close.addEventListener('click', hide);
    setTimeout(hide, 6000);
  });

  /* -------------------------------------------- copy to clipboard */
  $$('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = btn.getAttribute('data-copy');
      var done = function () {
        var label = $('.copy-label', btn);
        var original = label ? label.textContent : '';
        btn.classList.add('is-copied');
        if (label) label.textContent = 'Copied';
        setTimeout(function () {
          btn.classList.remove('is-copied');
          if (label) label.textContent = original;
        }, 1800);
      };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(done).catch(fallbackCopy);
      } else {
        fallbackCopy();
      }
      function fallbackCopy() {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); done(); } catch (e) { /* ignore */ }
        ta.remove();
      }
    });
  });

  /* ---------------------------------------------------- lightbox */
  var lightbox = $('#lightbox');
  var lbImg    = lightbox ? $('#lightbox-img', lightbox) : null;
  var lbCap    = lightbox ? $('#lightbox-cap', lightbox) : null;
  var lbItems  = [];
  var lbIndex  = 0;

  function openLightbox(index) {
    if (!lightbox || !lbItems.length) return;
    lbIndex = (index + lbItems.length) % lbItems.length;
    var item = lbItems[lbIndex];
    lbImg.src = item.src;
    lbImg.alt = item.caption || '';
    if (lbCap) lbCap.textContent = item.caption || '';
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function closeLightbox() {
    if (!lightbox || !lightbox.classList.contains('is-open')) return;
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
    if (lbImg) lbImg.src = '';
  }

  $$('.gallery-item').forEach(function (fig, i) {
    var img = $('img', fig);
    if (!img) return;
    var cap = $('figcaption', fig);
    lbItems.push({ src: img.getAttribute('data-full') || img.src, caption: cap ? cap.textContent.trim() : '' });
    var idx = lbItems.length - 1;
    fig.setAttribute('tabindex', '0');
    fig.setAttribute('role', 'button');
    fig.addEventListener('click', function () { openLightbox(idx); });
    fig.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openLightbox(idx); }
    });
  });

  if (lightbox) {
    $$('.lightbox-close', lightbox).forEach(function (b) { b.addEventListener('click', closeLightbox); });
    var prev = $('.lightbox-prev', lightbox);
    var next = $('.lightbox-next', lightbox);
    if (prev) prev.addEventListener('click', function (e) { e.stopPropagation(); openLightbox(lbIndex - 1); });
    if (next) next.addEventListener('click', function (e) { e.stopPropagation(); openLightbox(lbIndex + 1); });
    lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', function (e) {
      if (!lightbox.classList.contains('is-open')) return;
      if (e.key === 'ArrowLeft')  openLightbox(lbIndex - 1);
      if (e.key === 'ArrowRight') openLightbox(lbIndex + 1);
    });
  }

  /* ------------------------------------------------- video modal */
  var videoModal = $('#video-modal');
  var videoFrame = videoModal ? $('#video-frame', videoModal) : null;

  function closeVideo() {
    if (!videoModal) return;
    videoModal.classList.remove('is-open');
    document.body.style.overflow = '';
    if (videoFrame) videoFrame.src = '';
  }

  $$('[data-video]').forEach(function (card) {
    card.addEventListener('click', function () {
      if (!videoModal || !videoFrame) return;
      videoFrame.src = 'https://www.youtube-nocookie.com/embed/' + card.getAttribute('data-video') + '?autoplay=1&rel=0';
      videoModal.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    });
  });
  if (videoModal) {
    $$('.lightbox-close', videoModal).forEach(function (b) { b.addEventListener('click', closeVideo); });
    videoModal.addEventListener('click', function (e) { if (e.target === videoModal) closeVideo(); });
  }

  /* ------------------------------------------- file input preview */
  $$('.file-drop').forEach(function (drop) {
    var input = $('input[type="file"]', drop);
    var label = $('.file-name', drop);
    if (!input) return;

    function show(file) {
      if (!file) return;
      if (label) label.textContent = file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB';
      drop.classList.add('is-filled');
      var preview = $('.file-preview', drop);
      if (preview && file.type.indexOf('image/') === 0) {
        preview.src = URL.createObjectURL(file);
        preview.hidden = false;
      }
    }

    input.addEventListener('change', function () { show(input.files[0]); });
    ['dragenter', 'dragover'].forEach(function (ev) {
      drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('is-drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('is-drag'); });
    });
    drop.addEventListener('drop', function (e) {
      if (!e.dataTransfer || !e.dataTransfer.files.length) return;
      input.files = e.dataTransfer.files;
      show(input.files[0]);
    });
  });

  /* ------------------------------- amount sync on the payment form */
  var planSelect = $('#plan-select');
  if (planSelect) {
    var amountField = $('#amount-field');
    var amountShow  = $('#amount-display');
    var sync = function () {
      var opt = planSelect.options[planSelect.selectedIndex];
      var fee = opt ? opt.getAttribute('data-fee') : null;
      if (!fee) return;
      if (amountField) amountField.value = fee;
      if (amountShow)  amountShow.textContent = '৳' + Number(fee).toLocaleString();
    };
    planSelect.addEventListener('change', sync);
    sync();
  }

  // Selecting a wallet swaps the visible instructions panel.
  $$('input[name="method"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
      $$('[data-method-panel]').forEach(function (panel) {
        panel.hidden = panel.getAttribute('data-method-panel') !== radio.value;
      });
    });
    if (radio.checked) radio.dispatchEvent(new Event('change'));
  });

  /* --------------------------------------- guard double submission */
  $$('form[data-guard]').forEach(function (form) {
    form.addEventListener('submit', function () {
      var btn = form.querySelector('[type="submit"]');
      if (!btn) return;
      // Let the value still post, then disable visually on the next tick.
      setTimeout(function () {
        btn.classList.add('is-disabled');
        btn.setAttribute('aria-busy', 'true');
      }, 0);
    });
  });

  /* ------------------------------- mark the active nav item on load */
  $$('a[data-nav]').forEach(function (a) {
    if (a.getAttribute('href') === window.location.href) a.classList.add('is-active');
  });
})();
