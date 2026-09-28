<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tentang {{ $setting->site_title ?? 'Asa Production' }} - Mengubah Ide Menjadi Solusi Digital</title>
  <meta name="description" content="{{ $setting->site_title ?? 'Asa Production' }} membantu bisnis Anda tampil profesional secara online melalui jasa pembuatan website, aplikasi, dan sistem custom.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : asset('images/favicon.ico') }}" type="image/x-icon">

  {{-- Stylesheet yang sama dengan landing page & blog, agar warna, font, dan komponen (navbar, tombol, footer) konsisten --}}
  <link rel="stylesheet" href="{{ asset('css/grid.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/site-nav-shared.css') }}">
  <link rel="stylesheet" href="{{ asset('css/blog-theme.css') }}">

  <style>
    /* =========================================================
       SECTION TITLE THEME — konsisten dengan home.blade.php:
       teks gradient biru brand + garis aksen gradient di bawah
       judul section. Class "section-heading" untuk <h2>, dan
       "section-heading_bar" untuk <hr/> aksennya. */
    .section-heading{
      font-family: 'League Spartan', sans-serif;
      font-weight: 800;
      letter-spacing: .02em;
      background: linear-gradient(135deg, #16213a, var(--brand-light, #0051B5));
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      display: inline-block;
    }
    .section-heading_bar{
      width: 56px;
      height: 3px;
      border: none;
      border-radius: 999px;
      margin: 14px auto;
      background: linear-gradient(90deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
    }
  </style>
</head>
<body class="blog-body">

  @include('partials.site-nav')

  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Tentang Kami', 'i18n' => 'nav.about'],
  ]])

  {{-- ======================== MASTHEAD ======================== --}}
  <div class="about-masthead">
    <div class="container">
      <span class="eyebrow" data-i18n="aboutPage.mastheadEyebrow">Tentang Kami</span>
      <h1 data-i18n="aboutPage.mastheadTitle">Berkolaborasi & bertumbuh bersama membangun ekonomi digital Indonesia</h1>
      <p data-i18n="aboutPage.mastheadDesc" data-i18n-var-site="{{ $setting->site_title ?? 'Asa Production' }}">Bergabung bersama {{ $setting->site_title ?? 'Asa Production' }} untuk kemudahan dan kemajuan bisnis Anda. Kami menghadirkan solusi digital seperti website, aplikasi, hingga sistem custom yang dirancang sesuai kebutuhan Anda.</p>
    </div>
  </div>

  {{-- ======================== WHO WE ARE ======================== --}}
  <section class="about-section">
    <div class="about-hero-grid">
      <div>
        <span class="about-section_eyebrow" data-i18n="about.label">Who We Are</span>
        <h2 class="section-heading" data-i18n="aboutPage.sectionTitle" data-i18n-var-site="{{ $setting->site_title ?? 'Asa Production' }}">Tentang {{ $setting->site_title ?? 'Asa Production' }}</h2>
        <p>
          <span data-i18n-lang="id"><a href="https://www.instagram.com/asaproductionnewgen/" target="_blank" rel="noopener" style="color:var(--brand-light);">Asa Production</a> perusahaan yang bergerak dalam bidang IT dan penerbitan perangkat lunak (Software) yang sedang berkembang pesat, berlokasi di Kabupaten <a href="https://sidoarjokab.go.id/" target="_blank" rel="noopener" style="color:var(--brand-light);">Sidoarjo</a> - Jawa Timur. Didirikan pada tahun 2019 sebagai perusahaan rintisan berfokus pada bidang pengembangan perangkat lunak dan website. Kami menyediakan berbagai solusi dan produk seperti Aplikasi Warehouse Management System, Aplikasi Enterprise Resource Planning, Aplikasi Absensi Online, Aplikasi Customer Relationship Management, dan Aplikasi lainnya yang menunjang bisnis Anda.</span>
          <span data-i18n-lang="en" style="display:none"><a href="https://www.instagram.com/asaproductionnewgen/" target="_blank" rel="noopener" style="color:var(--brand-light);">Asa Production</a> is a fast-growing IT and software publishing company based in <a href="https://sidoarjokab.go.id/" target="_blank" rel="noopener" style="color:var(--brand-light);">Sidoarjo Regency</a>, East Java. Founded in 2019 as a startup, we focus on software and website development. We provide a range of solutions and products such as Warehouse Management System applications, Enterprise Resource Planning applications, Online Attendance applications, Customer Relationship Management applications, and other applications to support your business.</span>
        </p>
      </div>
      <div class="about-photo">
        <img src="{{ $setting->about_image ? asset('storage/'.$setting->about_image) : asset('images/parallax.jpg') }}" alt="{{ $setting->site_title ?? 'Asa Production' }}"/>
      </div>
    </div>
  </section>

  {{-- ======================== TEAM / FOUNDER ======================== --}}
  @if($team->count())
  <section class="about-section about-section--center" style="padding-bottom:90px;">
    <span class="about-section_eyebrow" data-i18n="aboutPage.teamEyebrow">Founder & Tim</span>
    <h2 class="section-heading" data-i18n="aboutPage.teamTitle">Tak kenal maka tak sayang</h2>
    <div class="team-grid">
      @foreach($team as $member)
      <div class="team-card">
        <div class="team-card_photo">
          @if($member->photo)
            <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}" loading="lazy"/>
          @endif
        </div>
        <div class="team-card_body">
          <p class="team-card_name">{{ $member->name }}</p>
          @if($member->role)
          <p class="team-card_role">@bi($member, 'role')</p>
          @endif
          @if($member->description)
          <p class="team-card_quote">&ldquo;@bi($member, 'description')&rdquo;</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  @include('partials.site-footer')

  <script src="{{ asset('js/i18n-data.js') }}"></script>
  <script src="{{ asset('js/i18n-lib.js') }}"></script>
  <script src="{{ asset('js/site-nav.js') }}"></script>
  <script>
    {{-- site-nav.js sudah memanggil I18n.init & bindLangSwitcher;
         di sini cukup expose helper yang dipakai chatbot widget --}}
    window.__siteLang = function () { return window.I18n ? window.I18n.getLang() : 'id'; };
    window.__t = function (key) { return window.I18n ? window.I18n.t(key) : key; };
  </script>

  @include('partials.chatbot-widget')
</body>
</html>
