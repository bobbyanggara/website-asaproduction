/**
 * Bikin "Daftar Isi" otomatis dari heading (H2/H3) di dalam .article-content,
 * dan tandai heading yang sedang aktif saat pembaca scroll (scrollspy ringan).
 *
 * Kalau artikel tidak punya heading sama sekali (mis. artikel lama yang masih
 * format teks polos), seluruh blok #articleToc disembunyikan otomatis.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var content = document.getElementById('articleContent');
    var tocNav = document.getElementById('articleToc');
    var tocList = document.getElementById('articleTocList');
    var tocToggle = document.getElementById('articleTocToggle');
    if (!content || !tocNav || !tocList) return;

    // Hanya ambil heading dari bahasa yang sedang aktif (supaya tidak dobel
    // saat konten punya versi ID + EN tersembunyi lewat data-i18n-lang).
    var scope = content.querySelector('[data-i18n-lang="id"]') || content;
    var headings = scope.querySelectorAll('h2, h3');

    if (!headings.length) {
      tocNav.style.display = 'none';
      return;
    }

    var usedIds = {};
    var frag = document.createDocumentFragment();

    headings.forEach(function (h, i) {
      if (!h.id) {
        var base = (h.textContent || 'bagian-' + (i + 1))
          .toLowerCase()
          .trim()
          .replace(/[^a-z0-9\s-]/g, '')
          .replace(/\s+/g, '-')
          .slice(0, 60) || 'bagian-' + (i + 1);
        var id = base;
        var n = 1;
        while (usedIds[id] || document.getElementById(id)) {
          id = base + '-' + (++n);
        }
        usedIds[id] = true;
        h.id = id;
      }

      var li = document.createElement('li');
      li.className = 'article-toc_item article-toc_item--' + h.tagName.toLowerCase();
      var a = document.createElement('a');
      a.href = '#' + h.id;
      a.textContent = h.textContent;
      li.appendChild(a);
      frag.appendChild(li);
    });

    tocList.appendChild(frag);

    if (tocToggle) {
      tocToggle.addEventListener('click', function () {
        var open = tocNav.classList.toggle('is-collapsed');
        tocToggle.setAttribute('aria-expanded', open ? 'false' : 'true');
      });
    }

    // Scrollspy ringan: highlight link heading yang sedang terlihat.
    var links = tocList.querySelectorAll('a');
    if ('IntersectionObserver' in window && links.length) {
      var byId = {};
      links.forEach(function (a) { byId[a.getAttribute('href').slice(1)] = a; });

      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          var link = byId[entry.target.id];
          if (!link) return;
          if (entry.isIntersecting) {
            links.forEach(function (a) { a.classList.remove('is-active'); });
            link.classList.add('is-active');
          }
        });
      }, { rootMargin: '-20% 0px -70% 0px' });

      headings.forEach(function (h) { observer.observe(h); });
    }
  });
})();
