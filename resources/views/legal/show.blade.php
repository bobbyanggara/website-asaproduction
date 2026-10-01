<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $page->title }} - {{ $legalApp->name ?? ($setting->site_title ?? 'Asa Production') }}</title>
  <meta name="description" content="{{ $page->metaDescription($setting) }}">
  <link rel="canonical" href="{{ $page->publicUrl() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : asset('images/favicon.ico') }}" type="image/x-icon">

  {{-- Stylesheet dasar yang sama dengan landing page, lalu tema khusus halaman legal --}}
  <link rel="stylesheet" href="{{ asset('css/grid.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/legal-theme.css') }}">
</head>
<body class="legal-body">

  @include('partials.legal-nav')

  @php
    $legalApp = $legalApp ?? null;
    $termsUrl = $legalApp ? route('legal.app.terms', $legalApp->slug) : route('legal.terms');
    $privacyUrl = $legalApp ? route('legal.app.privacy', $legalApp->slug) : route('legal.privacy');
    $dateId = $page->effective_date?->copy()->locale('id')->translatedFormat('j F Y');
    $dateEn = $page->effective_date?->copy()->locale('en')->translatedFormat('F j, Y');
  @endphp

  {{-- ======================== HERO ======================== --}}
  <header class="legal-hero">
    <div class="legal-container">
      @if($legalApp)
      <span class="legal-eyebrow">{{ $legalApp->name }}</span>
      @else
      <span class="legal-eyebrow" data-i18n="legal.eyebrow">Legal</span>
      @endif
      <h1>@bi($page, 'title')</h1>
      @if(filled($page->excerpt))
      <p class="legal-hero_desc">@bipair(\App\Models\LegalPage::replaceTokens($page->excerpt, $setting, $legalApp), \App\Models\LegalPage::replaceTokens($page->excerpt_en, $setting, $legalApp))</p>
      @endif
      <div class="legal-meta">
        @if($dateId)
        <span class="legal-chip">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15.5" rx="2.5" stroke="currentColor" stroke-width="2"/><path d="M3.5 10h17M8 3.5V7M16 3.5V7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          <span><span data-i18n="legal.updated">Terakhir diperbarui</span>: <span data-i18n-lang="id">{{ $dateId }}</span><span data-i18n-lang="en" style="display:none">{{ $dateEn }}</span></span>
        </span>
        @endif
        <button type="button" class="legal-chip legal-chip--print" onclick="window.print()">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 9V4h10v5M7 17H5a1.5 1.5 0 0 1-1.5-1.5v-5A1.5 1.5 0 0 1 5 9h14a1.5 1.5 0 0 1 1.5 1.5v5A1.5 1.5 0 0 1 19 17h-2M7 14h10v6.5H7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span data-i18n="legal.print">Cetak</span>
        </button>
      </div>
    </div>
  </header>

  {{-- Pindah antar dokumen --}}
  <div class="legal-tabs">
    <div class="legal-tabs_inner" role="tablist">
      <a href="{{ $termsUrl }}" class="{{ $activeSlug === 'terms' ? 'is-active' : '' }}" data-i18n="nav.terms">Ketentuan Layanan</a>
      <a href="{{ $privacyUrl }}" class="{{ $activeSlug === 'privacy' ? 'is-active' : '' }}" data-i18n="nav.privacy">Kebijakan Privasi</a>
    </div>
  </div>

  {{-- ======================== ISI ======================== --}}
  <main class="legal-layout">
    @if(count($idContent['toc']) || count($enContent['toc']))
    <aside class="legal-toc" id="legalToc" aria-label="Daftar isi">
      <button type="button" class="legal-toc_title" id="legalTocToggle" aria-expanded="true">
        <span data-i18n="legal.toc">Daftar Isi</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <ol class="legal-toc_list" @if($hasEn) data-i18n-lang="id" @endif>
        @foreach($idContent['toc'] as $t)
          <li><a href="#{{ $t['id'] }}">{{ $t['text'] }}</a></li>
        @endforeach
      </ol>
      @if($hasEn)
      <ol class="legal-toc_list" data-i18n-lang="en" style="display:none">
        @foreach($enContent['toc'] as $t)
          <li><a href="#{{ $t['id'] }}">{{ $t['text'] }}</a></li>
        @endforeach
      </ol>
      @endif
    </aside>
    @endif

    <article class="legal-card">
      @if($hasEn)
        <div class="legal-content" data-i18n-lang="id">{!! $idContent['html'] !!}</div>
        <div class="legal-content" data-i18n-lang="en" style="display:none">{!! $enContent['html'] !!}</div>
      @else
        <div class="legal-content">{!! $idContent['html'] !!}</div>
      @endif
    </article>
  </main>

  <section class="legal-cta">
    <div class="legal-cta_box">
      <div>
        <h3 data-i18n="legal.questionTitle">Masih punya pertanyaan?</h3>
        <p data-i18n="legal.questionDesc">Tim kami siap membantu menjelaskan isi dokumen ini atau menanggapi permintaan terkait data pribadi Anda.</p>
      </div>
      <a href="{{ route('contact') }}" class="legal-cta_btn">
        <span data-i18n="legal.contactBtn">Hubungi Kami</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </section>

  @include('partials.site-footer')

  <script src="{{ asset('js/i18n-data.js') }}"></script>
  <script src="{{ asset('js/i18n-lib.js') }}"></script>
  <script src="{{ asset('js/site-nav.js') }}"></script>
  <script>
    window.__siteLang = function () { return window.I18n ? window.I18n.getLang() : 'id'; };
    window.__t = function (key) { return window.I18n ? window.I18n.t(key) : key; };

    (function () {
      // Daftar isi: di mobile bisa dilipat.
      var toc = document.getElementById('legalToc');
      var toggle = document.getElementById('legalTocToggle');
      if (toc && toggle) {
        if (window.matchMedia('(max-width: 991px)').matches) { toc.classList.add('is-collapsed'); toggle.setAttribute('aria-expanded', 'false'); }
        toggle.addEventListener('click', function () {
          if (!window.matchMedia('(max-width: 991px)').matches) return;
          var collapsed = toc.classList.toggle('is-collapsed');
          toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
      }

      // Scroll-spy: tandai bagian yang sedang dibaca di daftar isi (hanya untuk bahasa yang tampil).
      var ticking = false;
      function visible(el) { return el.offsetParent !== null; }
      function spy() {
        ticking = false;
        var heads = Array.prototype.filter.call(document.querySelectorAll('.legal-content h2[id]'), visible);
        if (!heads.length) return;
        var current = heads[0].id;
        heads.forEach(function (h) { if (h.getBoundingClientRect().top <= 140) current = h.id; });
        document.querySelectorAll('.legal-toc_list a').forEach(function (a) {
          a.classList.toggle('is-active', a.getAttribute('href') === '#' + current && visible(a));
        });
      }
      window.addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(spy); } }, { passive: true });
      document.querySelectorAll('.lang-option').forEach(function (b) { b.addEventListener('click', function () { setTimeout(spy, 0); }); });
      spy();
    })();
  </script>

  @include('partials.chatbot-widget')
</body>
</html>
