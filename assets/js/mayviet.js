/* Mây Việt – hiệu ứng chuyển động dùng chung */
(function () {
  // ---- Ảnh an toàn: ảnh ngoài lỗi/chậm -> thử ảnh dự phòng (sau dấu #fb=, ngăn cách bằng |) -> cuối cùng là bản vẽ local ----
  function tach(url) { var i = url.indexOf('#fb='); return i < 0 ? { goc: url, ds: [] } : { goc: url.slice(0, i), ds: url.slice(i + 4).split('|').filter(Boolean) }; }
  function anToan(url, dat, hetCach) {
    var t = tach(url), ds = t.ds.slice(), xong = false;
    if (!/^https?:/i.test(t.goc) || !ds.length) return;
    (function thu(u, han) {
      var im = new Image(), tm = setTimeout(function () { if (!xong) { im.onload = im.onerror = null; tiep(); } }, han);
      function tiep() { clearTimeout(tm); var n = ds.shift(); if (n) { dat(n); thu(n, 8000); } else if (hetCach) hetCach(); }
      im.onload = function () { clearTimeout(tm); xong = true; };
      im.onerror = tiep; im.src = u;
    })(t.goc, 9000);
  }
  function quetAnh() {
    document.querySelectorAll('img[src^="http"]').forEach(function (img) {
      anToan(img.getAttribute('src'), function (n) { img.src = n; });
    });
    document.querySelectorAll('[style*="background-image"]').forEach(function (el) {
      var m = (el.getAttribute('style') || '').match(/url\((['"]?)(.*?)\1\)/);
      if (m) anToan(m[2], function (n) { el.style.backgroundImage = "url('" + n + "')"; });
    });
  }
  quetAnh();

  // Đưa mọi modal Bootstrap ra thẳng <body> để không bị lớp nền mờ che / kẹt trong khối có hiệu ứng
  document.querySelectorAll('.modal').forEach(function (m) { document.body.appendChild(m); });

  // Header đổ bóng khi cuộn
  var header = document.getElementById('site-header');
  function onScroll() { if (header) header.classList.toggle('is-scrolled', window.scrollY > 8); }
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();

  // Nút hỗ trợ 24/7
  var fab = document.getElementById('mv-fab'), fabBtn = document.getElementById('mv-fab-btn');
  if (fab && fabBtn) {
    fabBtn.addEventListener('click', function (e) { e.stopPropagation(); fab.classList.toggle('open'); });
    document.addEventListener('click', function () { fab.classList.remove('open'); });
  }

  // Sao chép mã ưu đãi
  var code = document.getElementById('mv-offer-code');
  if (code) code.addEventListener('click', function () {
    if (navigator.clipboard) navigator.clipboard.writeText(code.textContent.trim());
    var old = code.textContent; code.textContent = 'Đã sao chép ✓'; setTimeout(function () { code.textContent = old; }, 1400);
  });

  // Hiện dần các khối khi cuộn trang
  if ('IntersectionObserver' in window) {
    var sel = ['main section', '.sp-card', '.dest-tile', '.guide-card', '.rank-card', '.why-card', '.gallery-item', '.ve-tour', '.card.reveal'];
    var nodes = document.querySelectorAll(sel.join(','));
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
    nodes.forEach(function (n, i) {
      if (n.closest('.carousel') || n.closest('.no-reveal') || n.closest('.mv-hero')) return;
      n.classList.add('reveal-on-scroll');
      if (/sp-card|guide-card|rank-card|why-card|dest-tile/.test(n.className)) n.style.transitionDelay = (Math.min(i % 4, 3) * 80) + 'ms';
      io.observe(n);
    });
  }

  // Đếm số chạy (data-count)
  document.querySelectorAll('[data-count]').forEach(function (el) {
    var end = parseFloat(el.dataset.count), suffix = el.dataset.suffix || '', done = false;
    function run() {
      if (done) return; done = true; var t0 = performance.now(), dur = 1400;
      (function step(t) { var p = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(end * e).toLocaleString('vi-VN') + suffix; if (p < 1) requestAnimationFrame(step); })(t0);
    }
    if ('IntersectionObserver' in window) new IntersectionObserver(function (en, ob) { if (en[0].isIntersecting) { run(); ob.disconnect(); } }).observe(el); else run();
  });

  // Thanh tiến độ hạng
  document.querySelectorAll('.mv-progress>div[data-w]').forEach(function (b) { setTimeout(function () { b.style.width = b.dataset.w + '%'; }, 250); });
})();
