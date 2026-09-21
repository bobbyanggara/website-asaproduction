<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Portofolio - {{ $setting->site_title ?? 'Asa Production' }}</title>
  <meta name="description" content="Kumpulan hasil proyek website, aplikasi, dan sistem custom yang telah dikerjakan {{ $setting->site_title ?? 'Asa Production' }}.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : asset('images/favicon.ico') }}" type="image/x-icon">

  {{-- Stylesheet yang sama dengan landing page & halaman lain, agar warna, font, dan komponen (navbar, tombol, footer) konsisten --}}
  <link rel="stylesheet" href="{{ asset('css/grid.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/site-nav-shared.css') }}">
  <link rel="stylesheet" href="{{ asset('css/blog-theme.css') }}">
  <link rel="stylesheet" href="{{ asset('css/portfolio-theme.css') }}">

  {{--
    Animasi load-in untuk masthead — pola & keyframe disamakan persis
    dengan hero di home.blade.php (.hero-fade-in_item / @keyframes
    hero-fade-in-up), supaya kesannya konsisten dengan halaman utama.
    Untuk kartu di grid portofolio dipakai "wow fadeInUp" (scroll-in),
    sama seperti pola grid_4/kartu di halaman lain — jadi wow.js perlu
    dimuat di halaman ini (lihat script di akhir body).
  --}}
  <style>
    .hero-fade-in_item{
      opacity: 0;
      transform: translateY(22px);
      animation: hero-fade-in-up .9s cubic-bezier(.22,1,.36,1) forwards;
    }
    .hero-fade-in_item:nth-child(1){ animation-delay: .15s; }
    .hero-fade-in_item:nth-child(2){ animation-delay: .32s; }
    .hero-fade-in_item:nth-child(3){ animation-delay: .5s; }
    @keyframes hero-fade-in-up{
      to{ opacity: 1; transform: translateY(0); }
    }
    @media (prefers-reduced-motion: reduce){
      .hero-fade-in_item{ animation: none; opacity: 1; transform: none; }
      .wow{ animation: none !important; opacity: 1 !important; }
    }
  </style>
</head>
<body class="blog-body">

  @include('partials.site-nav')

  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Portofolio', 'i18n' => 'nav.portfolio'],
  ]])

  {{-- ======================== MASTHEAD ======================== --}}
  <div class="portfolio-masthead">
    <div class="container">
      <span class="eyebrow hero-fade-in_item" data-i18n="portfolioPage.eyebrow">Portofolio</span>
      <h1 class="hero-fade-in_item" data-i18n="portfolioPage.mastheadTitle">Temukan Inspirasimu</h1>
      <p class="hero-fade-in_item" data-i18n="portfolioPage.mastheadDesc" data-i18n-var-site="{{ $setting->site_title ?? 'Asa Production' }}">Kumpulan proyek website, aplikasi, dan sistem custom yang telah dikerjakan tim {{ $setting->site_title ?? 'Asa Production' }} untuk berbagai klien.</p>
    </div>
  </div>

  {{-- ======================== FILTER TABS ======================== --}}
  @if($categories->count())
  <div class="portfolio-filter-wrap">
    <div class="container">
      <div class="portfolio-filter" id="portfolioFilter" role="tablist">
        <button type="button" class="portfolio-filter_tab is-active" data-category="all" role="tab" aria-selected="true" data-i18n="portfolioPage.filterAll">All</button>
        @foreach($categories as $category)
        <button type="button" class="portfolio-filter_tab" data-category="{{ $category }}" role="tab" aria-selected="false">{{ $category }}</button>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  {{-- ======================== GRID ======================== --}}
  <div class="portfolio-wrap">
    <div class="container">
      @if($portfolios->count())
      <div class="portfolio-grid" id="portfolioGrid">
        @foreach($portfolios as $item)
        <div class="portfolio-card wow fadeInUp" data-wow-delay="{{ 0.08 * ($loop->index % 6) }}s" data-wow-duration="0.7s" data-category="{{ $item->category ?: 'all' }}">
          <button type="button" class="portfolio-card_thumb" data-full="{{ asset('storage/'.$item->image) }}" data-title="{{ $item->title }}" aria-label="{{ $item->title ?: 'Lihat detail proyek' }}" @unless($item->title) data-i18n-aria-label="portfolioPage.viewDetail" @endunless>
            <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" loading="lazy"/>
          </button>
          <div class="portfolio-card_body">
            @if($item->category)
            <span class="portfolio-card_tag">{{ $item->category }}</span>
            @endif
            @if($item->title)
            <h3 class="portfolio-card_title">{{ $item->title }}</h3>
            @endif
            @if($item->description)
            <p class="portfolio-card_desc">{{ $item->description }}</p>
            @endif
            @if($item->link)
            <a href="{{ $item->link }}" target="_blank" rel="noopener" class="portfolio-card_link" data-i18n="portfolioPage.viewProject">Lihat Proyek &rarr;</a>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      <p class="portfolio-empty-filtered" id="portfolioEmptyFiltered" hidden data-i18n="portfolioPage.emptyFiltered">Belum ada proyek pada kategori ini.</p>
      @else
      <div class="blog-empty" data-i18n="portfolioPage.empty">Belum ada portofolio yang dipublikasikan.</div>
      @endif
    </div>
  </div>

  {{-- ======================== LIGHTBOX ======================== --}}
  {{-- Overview gambar: bisa discroll/digeser ke samping (swipe di HP,
       drag mouse atau tombol panah di desktop) untuk pindah antar gambar. --}}
  <div class="portfolio-lightbox" id="portfolioLightbox">
    <button type="button" class="portfolio-lightbox_close" id="portfolioLightboxClose" data-i18n-aria-label="portfolioPage.lightboxClose" aria-label="Tutup">
      <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <button type="button" class="portfolio-lightbox_nav portfolio-lightbox_nav--prev" id="portfolioLightboxPrev" data-i18n-aria-label="portfolioPage.lightboxPrev" aria-label="Sebelumnya">
      <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button type="button" class="portfolio-lightbox_nav portfolio-lightbox_nav--next" id="portfolioLightboxNext" data-i18n-aria-label="portfolioPage.lightboxNext" aria-label="Berikutnya">
      <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="portfolio-lightbox_track" id="portfolioLightboxTrack"></div>
  </div>

  @include('partials.site-footer')

  <script src="{{ asset('js/i18n-data.js') }}"></script>
  <script src="{{ asset('js/i18n-lib.js') }}"></script>
  <script src="{{ asset('js/site-nav.js') }}"></script>
  <script src="{{ asset('js/portfolio-lightbox.js') }}"></script>
  <script src="{{ asset('js/portfolio-filter.js') }}"></script>
  {{--
    wow.js dimuat manual di sini (bukan lewat js/script.js seperti
    home.blade.php) karena halaman ini sengaja tidak memuat jQuery
    (site-nav.js & portfolio-filter.js di sini vanilla JS). wow.js
    sendiri tidak butuh jQuery, jadi cukup load + init langsung supaya
    "wow fadeInUp" pada kartu-kartu portofolio jalan.
  --}}
  <script src="{{ asset('js/wow.js') }}"></script>
  <script>
    if (typeof WOW === 'function') {
      new WOW().init();
    }
  </script>
  <script>
    {{-- site-nav.js sudah memanggil I18n.init & bindLangSwitcher;
         di sini cukup expose helper yang dipakai chatbot widget --}}
    window.__siteLang = function () { return window.I18n ? window.I18n.getLang() : 'id'; };
    window.__t = function (key) { return window.I18n ? window.I18n.t(key) : key; };
  </script>

  @include('partials.chatbot-widget')
</body>
</html>
