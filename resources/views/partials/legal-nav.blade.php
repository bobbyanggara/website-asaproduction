{{-- Navbar khusus halaman legal (Ketentuan Layanan & Kebijakan Privasi).
     Isi menu sengaja ringkas: Beranda (ke website utama), Ketentuan Layanan,
     Kebijakan Privasi. Tampilan "liquid glass" disamakan dengan navbar utama
     (partials/site-nav.blade.php) dan dikelola di sini saja (style navbar tidak
     ada di file CSS bersama). Perilaku toggle/dropdown/ganti bahasa tetap
     dipegang public/js/site-nav.js, jadi id & class di bawah jangan diubah.
     Breakpoint mobile = 991px, HARUS sama dengan MOBILE_QUERY di site-nav.js
     (JS memindahkan #langSwitcher ke #navLangSlot di <=991px). Kalau CSS
     dan JS beda breakpoint, tombol bendera nyasar ke dalam baris menu.
     #navToggle, #navMenu, #navLangSlot, #langSwitcher, #langToggle,
     #langCurrentFlag, .lang-option, .dropdown-toggle, .site-nav_utility.

     Variabel: $setting (Setting), $activeSlug ('terms' | 'privacy'), $legalApp (LegalApp|null: dokumen milik aplikasi). --}}
<style>
  /* Wajib border-box: stylesheet template (style.css) tidak menjamin ini, dan
     tanpa border-box padding .site-nav_inner ditambahkan DI LUAR lebar 100%
     sehingga isi navbar (tombol bendera) menjorok keluar kapsul. */
  .site-nav, .site-nav *, .site-nav *::before, .site-nav *::after{ box-sizing: border-box; }
  .site-nav{
    position: fixed; top: 0; left: 50%; z-index: 1000; width: 100%;
    transform: translateX(-50%);
    border-radius: 0; background: transparent; border: 1px solid transparent;
    transition: top .5s cubic-bezier(.22,1,.36,1), width .5s cubic-bezier(.22,1,.36,1), border-radius .5s cubic-bezier(.22,1,.36,1), background .45s ease, border-color .45s ease, box-shadow .45s ease;
  }
  .site-nav.is-scrolled{
    top: max(14px, env(safe-area-inset-top));
    width: min(1100px, calc(100% - 24px));
    border-radius: 999px;
    background: linear-gradient(180deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,.02) 40%, rgba(255,255,255,.04) 100%), rgba(9,20,38,.5);
    -webkit-backdrop-filter: blur(36px) saturate(200%) brightness(1.1);
    backdrop-filter: blur(36px) saturate(200%) brightness(1.1);
    border-color: rgba(255,255,255,.34);
    box-shadow: 0 22px 54px -14px rgba(0,10,26,.6), 0 4px 16px -4px rgba(0,10,26,.4), inset 0 1.5px 0 rgba(255,255,255,.6), inset 0 -1.5px 0 rgba(0,0,0,.22);
  }
  .site-nav_inner{ position: relative; display: flex; align-items: center; gap: 18px; width: 100%; max-width: 1180px; margin: 0 auto; padding: 12px 16px 12px 20px; transition: padding .4s cubic-bezier(.22,1,.36,1); }
  .site-nav.is-scrolled .site-nav_inner{ padding-top: 8px; padding-bottom: 8px; }
  .site-nav_brand{ display: flex; align-items: center; gap: 10px; flex: 0 0 auto; text-decoration: none; }
  .site-nav_brand img{ height: 32px; width: auto; display: block; }
  .site-nav_brand span{ font-family: 'League Spartan', sans-serif; color: #fff; font-size: 16px; line-height: 1; font-weight: 800; letter-spacing: .01em; white-space: nowrap; transition: color .2s ease; }
  .site-nav_brand:hover span{ color: var(--brand-accent, #2E8BFF); }

  .site-nav_menu{ display: flex; align-items: center; gap: 6px; list-style: none; margin: 0 0 0 auto; padding: 0; }
  .site-nav_menu > li{ display: flex; align-items: center; }
  .site-nav_menu > li > a{
    display: inline-flex; align-items: center; gap: 7px;
    color: rgba(255,255,255,.9); font-size: 14px; line-height: 1; font-weight: 600;
    letter-spacing: .02em; text-decoration: none; text-transform: none;
    padding: 10px 16px; border-radius: 999px;
    transition: color .2s ease, background .2s ease;
  }
  .site-nav_menu > li > a:hover{ color: #fff; background: rgba(255,255,255,.1); }
  .site-nav_menu > li > a.is-active{ color: #fff; background: linear-gradient(180deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5)); box-shadow: 0 6px 16px -6px rgba(46,139,255,.7), inset 0 1px 0 rgba(255,255,255,.3); }
  .site-nav_menu > li > a svg{ flex: 0 0 auto; }

  .site-nav_utility{ display: flex; align-items: center; gap: 10px; flex: 0 0 auto; }
  .site-nav_lang{ position: relative; }
  .dropdown-toggle{ background: none; border: none; color: #fff; font-family: inherit; cursor: pointer; padding: 0; }
  .lang-toggle{ display: flex; align-items: center; gap: 7px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18); padding: 6px 10px; border-radius: 30px; line-height: 1; transition: background .2s ease; }
  .lang-toggle:hover{ background: rgba(255,255,255,.18); }
  .lang-toggle i{ font-size: 9px; opacity: .8; }
  .lang-flag-icon{ display: block; width: 22px; height: 15px; border-radius: 3px; overflow: hidden; box-shadow: 0 0 0 1px rgba(255,255,255,.3); flex-shrink: 0; }
  .lang-flag-icon svg{ display: block; width: 100%; height: 100%; }
  .dropdown-menu{
    list-style: none; margin: 0; padding: 8px; position: absolute; top: calc(100% + 12px); right: 0; min-width: 140px;
    background: linear-gradient(180deg, rgba(255,255,255,.1) 0%, rgba(255,255,255,0) 50%), rgba(9,16,32,.78);
    -webkit-backdrop-filter: blur(30px) saturate(200%); backdrop-filter: blur(30px) saturate(200%);
    border: 1px solid rgba(255,255,255,.22); border-radius: 16px;
    box-shadow: 0 16px 34px -10px rgba(0,0,0,.5), inset 0 1.5px 0 rgba(255,255,255,.28);
    opacity: 0; visibility: hidden; transform: translateY(6px) scale(.96);
    transition: opacity .22s ease, transform .3s cubic-bezier(.22,1,.36,1), visibility .22s ease; z-index: 20;
  }
  .has-dropdown.is-open .dropdown-menu, #langSwitcher:hover .dropdown-menu{ opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
  .lang-menu li + li{ margin-top: 4px; }
  .lang-option{ display: flex; align-items: center; gap: 8px; width: 100%; height: 32px; background: none; border: 2px solid transparent; border-radius: 8px; padding: 0 10px 0 6px; cursor: pointer; }
  .lang-option:hover{ background: rgba(255,255,255,.08); }
  .lang-option.is-active{ border-color: var(--brand-accent, #2E8BFF); background: rgba(255,255,255,.08); }
  .lang-option-label{ color: #fff; font-size: 12px; line-height: 1; white-space: nowrap; }
  #navLangSlot:empty{ display: none; }

  .site-nav_toggle{ display: none; flex: 0 0 auto; width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.16); position: relative; cursor: pointer; }
  .site-nav_toggle span{ position: absolute; left: 9px; right: 9px; height: 2px; background: #fff; border-radius: 2px; transition: transform .3s cubic-bezier(.22,1,.36,1), opacity .2s ease, top .3s cubic-bezier(.22,1,.36,1); }
  .site-nav_toggle span:nth-child(1){ top: 13px; }
  .site-nav_toggle span:nth-child(2){ top: 18px; }
  .site-nav_toggle span:nth-child(3){ top: 23px; }
  .site-nav_toggle.is-open span:nth-child(1){ top: 18px; transform: rotate(45deg); }
  .site-nav_toggle.is-open span:nth-child(2){ opacity: 0; }
  .site-nav_toggle.is-open span:nth-child(3){ top: 18px; transform: rotate(-45deg); }

  @media (max-width: 991px){
    .site-nav_toggle{ display: block; }
    .site-nav_utility{ margin-left: auto; gap: 10px; }
    .site-nav_inner{ gap: 12px; }
    .site-nav_brand{ min-width: 0; }
    .site-nav_brand span{ overflow: hidden; text-overflow: ellipsis; }
    .lang-toggle{ padding: 5px 8px; }
    .lang-flag-icon{ width: 20px; height: 14px; }
    .site-nav_menu{
      position: absolute; top: calc(100% + 10px); left: 0; right: 0; margin: 0;
      flex-direction: column; align-items: stretch; gap: 2px; padding: 12px; border-radius: 22px;
      max-height: 0; overflow: hidden auto; opacity: 0; pointer-events: none;
      background: linear-gradient(180deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,.02) 40%), rgba(9,18,36,.88);
      -webkit-backdrop-filter: blur(34px) saturate(200%); backdrop-filter: blur(34px) saturate(200%);
      border: 1px solid rgba(255,255,255,.26); box-shadow: 0 20px 46px -12px rgba(0,0,0,.55), inset 0 1.5px 0 rgba(255,255,255,.28);
      transition: max-height .35s cubic-bezier(.22,1,.36,1), opacity .25s ease;
    }
    .site-nav_menu.is-open{ max-height: 420px; opacity: 1; pointer-events: auto; }
    .site-nav_menu > li{ display: block; }
    .site-nav_menu > li > a{ display: flex; padding: 14px 16px; border-radius: 14px; }
    #navLangSlot{ border-top: 1px solid rgba(255,255,255,.1); margin-top: 6px; padding-top: 8px; }
    #navLangSlot .lang-toggle{ width: 100%; background: none; border: none; border-radius: 14px; padding: 12px 16px; justify-content: space-between; }
    #langSwitcher .dropdown-menu{ position: static; transform: none; min-width: 0; width: 100%; padding: 0; background: transparent; border: none; box-shadow: none; -webkit-backdrop-filter: none; backdrop-filter: none; max-height: 0; overflow: hidden; opacity: 1; visibility: visible; transition: max-height .25s ease; }
    #langSwitcher.is-open .dropdown-menu{ max-height: 200px; }
    #navLangSlot .lang-option{ width: 100%; height: auto; border-radius: 0; padding: 12px 40px; }
  }
  @media (max-width: 380px){ .site-nav_brand span{ font-size: 14px; } .site-nav_brand img{ height: 28px; } }
  @media (prefers-reduced-motion: reduce){ .site-nav{ transition: none; } }
</style>
@php
  $navApp = $legalApp ?? null;
  $navTermsUrl = $navApp ? route('legal.app.terms', $navApp->slug) : route('legal.terms');
  $navPrivacyUrl = $navApp ? route('legal.app.privacy', $navApp->slug) : route('legal.privacy');
@endphp
<nav class="site-nav" id="site-nav" aria-label="Navigasi utama">
  <div class="site-nav_inner">
    <a href="{{ route('home') }}" class="site-nav_brand">
      <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}" alt="{{ $setting->site_title ?? 'Asa Production' }}"/>
      <span>{{ $setting->site_title ?? 'Asa Production' }}</span>
    </a>

    <ul class="site-nav_menu" id="navMenu">
      <li>
        <a href="{{ route('home') }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3.5 10.5L12 3.5l8.5 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.5 9.5V19a1.2 1.2 0 0 0 1.2 1.2H9.7v-5.4h4.6v5.4h3a1.2 1.2 0 0 0 1.2-1.2V9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span data-i18n="nav.home">Beranda</span>
        </a>
      </li>
      <li><a href="{{ $navTermsUrl }}" class="{{ ($activeSlug ?? '') === 'terms' ? 'is-active' : '' }}" @if(($activeSlug ?? '') === 'terms') aria-current="page" @endif data-i18n="nav.terms">Ketentuan Layanan</a></li>
      <li><a href="{{ $navPrivacyUrl }}" class="{{ ($activeSlug ?? '') === 'privacy' ? 'is-active' : '' }}" @if(($activeSlug ?? '') === 'privacy') aria-current="page" @endif data-i18n="nav.privacy">Kebijakan Privasi</a></li>
      {{-- Slot kosong: site-nav.js memindahkan #langSwitcher ke sini di tampilan mobile. --}}
      <li class="site-nav_lang-item" id="navLangSlot"></li>
    </ul>

    <div class="site-nav_utility">
      <div class="site-nav_lang has-dropdown" id="langSwitcher">
        <button type="button" class="dropdown-toggle lang-toggle" id="langToggle" aria-haspopup="true" aria-expanded="false" data-i18n-aria-label="nav.langChangeLabel" aria-label="Change language">
          <span class="lang-flag-icon" id="langCurrentFlag" aria-hidden="true">
            <svg viewBox="0 0 3 2" xmlns="http://www.w3.org/2000/svg"><rect width="3" height="1" fill="#CE1126"/><rect y="1" width="3" height="1" fill="#FFFFFF"/></svg>
          </span>
          <i class="fa fa-chevron-down" aria-hidden="true"></i>
        </button>
        <ul class="dropdown-menu lang-menu" role="listbox" data-i18n-aria-label="nav.langChooseLabel" aria-label="Choose language">
          <li role="presentation">
            <button type="button" class="lang-option is-active" data-lang="id" role="option" aria-checked="true" aria-label="Bahasa Indonesia">
              <span class="lang-flag-icon" aria-hidden="true"><svg viewBox="0 0 3 2" xmlns="http://www.w3.org/2000/svg"><rect width="3" height="1" fill="#CE1126"/><rect y="1" width="3" height="1" fill="#FFFFFF"/></svg></span>
              <span class="lang-option-label" aria-hidden="true">Indonesia</span>
            </button>
          </li>
          <li role="presentation">
            <button type="button" class="lang-option" data-lang="en" role="option" aria-checked="false" aria-label="English">
              <span class="lang-flag-icon" aria-hidden="true"><svg viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg"><rect width="60" height="30" fill="#00247d"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/><path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="2"/><path d="M30,0 V30 M0,15 H60" stroke="#fff" stroke-width="10"/><path d="M30,0 V30 M0,15 H60" stroke="#cf142b" stroke-width="6"/></svg></span>
              <span class="lang-option-label" aria-hidden="true">English</span>
            </button>
          </li>
        </ul>
      </div>
      <button class="site-nav_toggle" id="navToggle" type="button" data-i18n-aria-label="nav.toggleLabel" aria-label="Toggle menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</nav>
<script>
  // Morph navbar jadi kapsul kaca setelah halaman di-scroll (sama seperti navbar utama).
  (function () {
    var nav = document.getElementById('site-nav');
    if (!nav) return;
    var ticking = false;
    function update() { nav.classList.toggle('is-scrolled', window.scrollY >= 24); ticking = false; }
    window.addEventListener('scroll', function () { if (!ticking) { window.requestAnimationFrame(update); ticking = true; } }, { passive: true });
    update();
  })();
</script>
