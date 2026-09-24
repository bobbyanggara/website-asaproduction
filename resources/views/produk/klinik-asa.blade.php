<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Klinik ASA - Sistem Manajemen Klinik - {{ $setting->site_title ?? 'Asa Production' }}</title>
  <meta name="description" content="{{ $page->meta_description ?? 'Klinik ASA, sistem manajemen klinik terintegrasi (pendaftaran, rekam medis, kasir & apotek) oleh '.($setting->site_title ?? 'Asa Production').'.' }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : asset('images/favicon.ico') }}" type="image/x-icon">

  {{-- Stylesheet yang sama dengan halaman lain, agar warna, font, dan komponen (navbar, tombol, footer) konsisten --}}
  <link rel="stylesheet" href="{{ asset('css/grid.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/site-nav-shared.css') }}">
  <link rel="stylesheet" href="{{ asset('css/blog-theme.css') }}">

  <script src="{{ asset('js/jquery.js') }}"></script>
  <script src="{{ asset('js/jquery-migrate-1.2.1.js') }}"></script>
  <script src='{{ asset('js/device.min.js') }}'></script>

  @include('partials.solusi-page-style')
</head>
<body class="blog-body">

  @include('partials.site-nav')

  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Klinik ASA'],
  ]])

  {{-- ======================== KONTEN ======================== --}}
  <main>

    {{-- HERO --}}
    <section class="solusi-hero">
      <div class="container solusi-hero_inner">
        <div class="solusi-hero_text">
          <span class="solusi-eyebrow hero-fade-in_item"><i class="fa fa-user-md" aria-hidden="true"></i> Produk Klinik ASA</span>
          <h1 class="hero-fade-in_item">{{ $page->hero_title ?: 'Klinik ASA, Sistem Manajemen Klinik Terintegrasi untuk Pelayanan yang Lebih Cepat dan Rapi' }}</h1>
          <p class="hero-fade-in_item">{{ $page->hero_subtitle ?: 'Klinik ASA menyatukan pendaftaran pasien, rekam medis elektronik, jadwal dokter, hingga kasir dan apotek dalam satu sistem — memudahkan tenaga medis dan staf klinik melayani pasien lebih cepat dan tertata.' }}</p>
          <a href="https://wa.me/{{ $setting->whatsapp_number }}?text={{ urlencode($setting->whatsapp_message) }}" target="_blank" rel="noopener" class="btn btn-whatsapp solusi-hero_cta hero-fade-in_item">
            <svg viewBox="0 0 32 32" width="20" height="20" fill="currentColor" style="vertical-align:middle;flex-shrink:0;">
              <path d="M16.001 3C9.373 3 4 8.373 4 15c0 2.386.7 4.607 1.906 6.475L4 29l7.72-1.867A11.94 11.94 0 0 0 16.001 27C22.628 27 28 21.627 28 15S22.628 3 16.001 3zm0 21.818a9.77 9.77 0 0 1-4.98-1.363l-.357-.212-4.583 1.108 1.127-4.47-.233-.367A9.78 9.78 0 0 1 6.182 15c0-5.415 4.404-9.818 9.819-9.818S25.818 9.585 25.818 15 21.415 24.818 16.001 24.818zm5.396-7.34c-.296-.148-1.75-.864-2.021-.963-.271-.099-.469-.148-.667.148-.198.296-.766.963-.939 1.161-.173.198-.346.222-.642.074-.296-.148-1.249-.46-2.379-1.467-.879-.784-1.472-1.753-1.645-2.049-.173-.296-.018-.456.13-.604.134-.133.296-.346.444-.519.148-.173.198-.297.296-.494.099-.198.05-.371-.025-.519-.074-.148-.667-1.607-.914-2.202-.24-.577-.485-.499-.667-.508l-.568-.01c-.198 0-.519.074-.79.371-.271.297-1.037 1.014-1.037 2.472s1.062 2.868 1.21 3.066c.148.198 2.089 3.19 5.062 4.474.707.305 1.259.487 1.689.623.71.226 1.355.194 1.866.118.569-.085 1.75-.716 1.997-1.407.247-.692.247-1.284.173-1.407-.074-.123-.271-.198-.568-.346z"/>
            </svg>
            <span>Whatsapp Kami</span>
          </a>
        </div>
        @if($page->hero_image)
        <div class="solusi-hero_media hero-fade-in_item" style="animation-delay:.7s;">
          <img src="{{ asset('storage/'.$page->hero_image) }}" alt="{{ $page->hero_title ?: 'Produk Klinik ASA' }}" loading="lazy">
        </div>
        @endif
      </div>
    </section>

    @include('partials.solusi-clients', ['partners' => $clients])

    {{-- PREVIEW PRODUK --}}
    @if($page->preview_media)
    <section class="well solusi-preview center">
      <div class="container">
        <h2>{{ $page->preview_title ?: 'Preview Produk Klinik ASA' }}</h2>
        <hr/>
        <div class="solusi-preview_media">
          @if(\Illuminate\Support\Str::endsWith(strtolower($page->preview_media), ['.mp4', '.webm']))
            <video src="{{ asset('storage/'.$page->preview_media) }}" autoplay muted loop playsinline></video>
          @else
            <img src="{{ asset('storage/'.$page->preview_media) }}" alt="{{ $page->preview_title ?: 'Preview Produk Klinik ASA' }}" loading="lazy">
          @endif
        </div>
        @if($page->preview_caption)
        <p class="solusi-preview_caption">{{ $page->preview_caption }}</p>
        @endif
      </div>
    </section>
    @endif

    {{-- KEUNGGULAN --}}
    @if($benefits->count())
    <section class="well solusi-benefits center" id="keunggulan">
      <div class="container">
        <h2>Keunggulan</h2>
        <hr/>
        <div class="row">
          @foreach($benefits as $benefit)
          <div class="grid_4 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index % 3) }}s" data-wow-duration="0.8s">
            <div class="solusi-benefit-card">
              <div class="solusi-benefit-card_icon">
                @if($benefit->icon && str_contains($benefit->icon, '/'))
                  <img src="{{ asset('storage/'.$benefit->icon) }}" alt="{{ $benefit->title }}" loading="lazy">
                @else
                  <i class="fa {{ $benefit->icon ?: 'fa-star' }}" aria-hidden="true"></i>
                @endif
              </div>
              <h3>{{ $benefit->title }}</h3>
              <p>{{ $benefit->description }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    @include('partials.solusi-faqs', ['faqs' => $faqs])

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/i18n-data.js') }}"></script>
  <script src="{{ asset('js/i18n-lib.js') }}"></script>
  <script src="{{ asset('js/site-nav.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script>
    window.setTimeout(function () {
      if (typeof WOW === 'function') {
        return;
      }
      var s = document.createElement('script');
      s.src = '{{ asset('js/wow.js') }}';
      s.onload = function () {
        if (typeof WOW === 'function') {
          new WOW().init();
        }
      };
      document.body.appendChild(s);
    }, 150);
  </script>
  <script>
    window.__siteLang = function () { return window.I18n ? window.I18n.getLang() : 'id'; };
    window.__t = function (key) { return window.I18n ? window.I18n.t(key) : key; };
  </script>

  @include('partials.chatbot-widget')
</body>
</html>
