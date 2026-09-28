/*========================================================
  Portfolio image overview — lightbox dengan track yang bisa
  discroll/digeser ke samping (swipe di HP, drag mouse atau
  tombol panah di desktop) untuk berpindah antar gambar.

  Dipakai bersama di halaman portfolio (resources/views/
  portfolio.blade.php) maupun galeri portofolio di halaman
  utama (resources/views/home.blade.php). Trigger cukup
  ditandai atribut data-full (opsional data-title).
  Vanilla JS, tanpa dependensi jQuery.
========================================================*/
(function () {
  var triggers = document.querySelectorAll('[data-full]');
  var lightbox = document.getElementById('portfolioLightbox');
  var track = document.getElementById('portfolioLightboxTrack');

  if (!triggers.length || !lightbox || !track) { return; }

  var items = Array.prototype.slice.call(triggers);
  var closeBtn = document.getElementById('portfolioLightboxClose');
  var prevBtn = document.getElementById('portfolioLightboxPrev');
  var nextBtn = document.getElementById('portfolioLightboxNext');
  var currentIndex = 0;

  // Bangun satu slide per gambar di dalam track.
  items.forEach(function (el) {
    var slide = document.createElement('figure');
    slide.className = 'portfolio-lightbox_slide';

    var img = document.createElement('img');
    img.loading = 'lazy';
    img.src = el.getAttribute('data-full');
    img.alt = el.getAttribute('data-title') || '';
    slide.appendChild(img);

    var title = el.getAttribute('data-title');
    var desc = el.getAttribute('data-desc');

    if (title || desc) {
      var caption = document.createElement('figcaption');
      caption.className = 'portfolio-lightbox_caption';

      if (title) {
        var captionTitle = document.createElement('span');
        captionTitle.className = 'portfolio-lightbox_caption-title';
        captionTitle.textContent = title;
        caption.appendChild(captionTitle);
      }

      if (desc) {
        var captionDesc = document.createElement('p');
        captionDesc.className = 'portfolio-lightbox_caption-desc';
        captionDesc.textContent = desc;
        caption.appendChild(captionDesc);
      }

      slide.appendChild(caption);
    }

    track.appendChild(slide);
  });

  var slides = Array.prototype.slice.call(track.children);

  function updateNavVisibility() {
    if (!prevBtn || !nextBtn) { return; }
    var multiple = slides.length > 1;
    prevBtn.hidden = !multiple;
    nextBtn.hidden = !multiple;
  }
  updateNavVisibility();

  function scrollToIndex(index, smooth) {
    if (index < 0) { index = slides.length - 1; }
    if (index >= slides.length) { index = 0; }
    currentIndex = index;
    var slide = slides[index];
    if (slide) {
      track.scrollTo({ left: slide.offsetLeft, behavior: smooth ? 'smooth' : 'auto' });
    }
  }

  function openLightbox(index) {
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    scrollToIndex(index, false);
  }

  function closeLightbox() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  items.forEach(function (el, i) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      openLightbox(i);
    });
  });

  if (closeBtn) { closeBtn.addEventListener('click', closeLightbox); }

  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox) { closeLightbox(); }
  });

  document.addEventListener('keydown', function (e) {
    if (!lightbox.classList.contains('is-open')) { return; }
    if (e.key === 'Escape') { closeLightbox(); }
    if (e.key === 'ArrowRight') { scrollToIndex(currentIndex + 1, true); }
    if (e.key === 'ArrowLeft') { scrollToIndex(currentIndex - 1, true); }
  });

  if (prevBtn) {
    prevBtn.addEventListener('click', function () { scrollToIndex(currentIndex - 1, true); });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function () { scrollToIndex(currentIndex + 1, true); });
  }

  // Sinkronkan currentIndex saat user scroll/swipe manual di track.
  var scrollTimer;
  track.addEventListener('scroll', function () {
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function () {
      var scrollLeft = track.scrollLeft;
      var closest = 0;
      var min = Infinity;
      slides.forEach(function (slide, i) {
        var diff = Math.abs(slide.offsetLeft - scrollLeft);
        if (diff < min) { min = diff; closest = i; }
      });
      currentIndex = closest;
    }, 100);
  });

  // Drag-to-scroll pakai mouse (desktop), pelengkap swipe touch native.
  var isDown = false;
  var startX = 0;
  var startScrollLeft = 0;

  track.addEventListener('mousedown', function (e) {
    isDown = true;
    track.classList.add('is-dragging');
    startX = e.pageX;
    startScrollLeft = track.scrollLeft;
  });

  window.addEventListener('mouseup', function () {
    if (!isDown) { return; }
    isDown = false;
    track.classList.remove('is-dragging');
  });

  window.addEventListener('mousemove', function (e) {
    if (!isDown) { return; }
    e.preventDefault();
    track.scrollLeft = startScrollLeft - (e.pageX - startX);
  });

  // Reposisikan slide aktif saat ukuran layar berubah (rotasi HP, resize).
  window.addEventListener('resize', function () {
    if (lightbox.classList.contains('is-open')) {
      scrollToIndex(currentIndex, false);
    }
  });
})();
