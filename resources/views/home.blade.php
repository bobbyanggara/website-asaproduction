<!DOCTYPE html>
<html lang="id">
<head>
  <title>{{ $setting->site_title }} - {{ $setting->brand_slogan }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : asset('images/favicon.ico') }}" type="image/x-icon">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/grid.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}"/>
  <link rel="stylesheet" href="{{ asset('css/owl-carousel.css') }}"/>
  {{-- Dipakai untuk overview gambar portofolio yang bisa discroll ke samping,
       konsisten dengan halaman /portfolio --}}
  <link rel="stylesheet" href="{{ asset('css/portfolio-theme.css') }}"/>

  <script src="{{ asset('js/jquery.js') }}"></script>
  <script src="{{ asset('js/jquery-migrate-1.2.1.js') }}"></script>
  <script src='{{ asset('js/device.min.js') }}'></script>
  <script src="{{ asset('js/i18n-data.js') }}"></script>
  <script src="{{ asset('js/i18n-lib.js') }}"></script>

  <style>
    /* ===== Hero section fix ===== */
    header.vide .vide_content{
      padding-top: 80px;
      padding-bottom: 80px;
    }
    header.vide .hero-text h2{
      font-size: clamp(24px, 4vw, 40px);
      line-height: 1.25;
      font-weight: 700;
      font-family: 'League Spartan', sans-serif !important;
    }
    header.vide .hero-text h3{
      font-size: clamp(15px, 2vw, 18px);
      line-height: 1.5;
      font-weight: 400;
      opacity: .9;
    }
    header.vide .brand_name,
    header.vide .brand_name a{
      font-size: clamp(18px, 2.2vw, 22px);
      font-family: 'League Spartan', sans-serif !important;
    }
    header.vide .brand_slogan{
      font-size: clamp(12px, 1.4vw, 14px);
      opacity: .85;
    }

    /* ===== Footer brand font: samakan dengan navbar (League Spartan) =====
       Selector dibuat lebih spesifik (footer#contact ...) supaya menang
       lawan aturan font di style.css tema, yang kemungkinan pakai
       selector ber-ID juga. text-transform:none dipaksa juga karena
       tema aslinya bikin teks ini jadi KAPITAL SEMUA ("ASA PRODUCTION"),
       padahal navbar tampil normal ("Asa Production"). */
    footer#contact .footer-brand span{
      font-family: 'League Spartan', sans-serif !important;
      text-transform: none !important;
    }

    /* =========================================================
       SECTION TITLE THEME — dipakai di SEMUA section & halaman
       (home, about, /solusi/*, /produk/*, dan partial yang dipakai
       bersama seperti Clients/FAQ/Portofolio), supaya judul section
       konsisten: teks gradient biru brand + garis aksen gradient
       di bawahnya. Cukup tambahkan class ini ke markup:
         <h2 class="section-heading">Judul</h2>
         <hr class="section-heading_bar"/>
       (hr boleh dilewati kalau section tidak punya garis bawah,
       misalnya heading gaya "eyebrow" di halaman About.) */
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

    /* ===== Subtle hover micro-interactions ===== */
    .well__ins1 .grid_4{
      transition: transform .35s cubic-bezier(.22,1,.36,1);
    }
    .well__ins1 .grid_4:hover{
      transform: translateY(-6px);
    }
    .faq-item{
      transition: box-shadow .3s ease, transform .3s ease;
    }
    .faq-item:hover{
      transform: translateY(-2px);
    }

    /* ===== Hero load-in animation ===== */
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
    }

    /* ===== Roket mengorbit logo hero — efek 3D sungguhan =====
       Ini BUKAN sekadar rotate() datar (yang cuma muter di satu bidang
       rata, tidak terasa 3D). Di sini orbitnya berbentuk elips (Rx=100,
       Ry=34) untuk mensimulasikan lingkaran yang dilihat miring/perspektif,
       DAN setiap titik jalur punya scale() + z-index sendiri berdasarkan
       posisi vertikal: separuh bawah elips (y positif) = "lebih dekat ke
       kamera" → roket membesar & z-index di ATAS logo; separuh atas elips
       (y negatif) = "lebih jauh" → roket mengecil & z-index di BAWAH logo,
       jadi benar-benar lewat DI BELAKANG logo — itulah yang memberi
       ilusi 3D, bukan cuma 2D muter di tempat.
       Semua angka translate/scale di keyframe ini dihitung dari rumus
       parametrik elips x=Rx·cosθ, y=Ry·sinθ (θ=0..360°, tiap 30°). Arah
       hidung roket dihitung dari turunan (tangen) rumus itu sendiri —
       φ=atan2(-Rx·sinθ, -Ry·cosθ) — bukan pendekatan "θ+180°" (yang cuma
       pas untuk lingkaran sempurna, meleset sampai ±30° di elips lonjong
       begini). Nilainya juga dibiarkan naik terus tanpa di-mod ke 0–360°
       supaya putarannya mulus satu arah, tidak "loncat balik" di
       tengah siklus. */
    .brand_logo-wrap{
      position: relative;
      width: 140px;
      height: 140px;
      max-width: 100%;
      margin: 0 0 6px 0;
    }
    .brand_logo-wrap img,
    .brand_logo-wrap video{
      display: block;
      width: 100%;
      height: 100%;
      object-fit: contain;
      margin: 0;
      position: relative;
      z-index: 2;
    }
    /* Garis jalur orbit sengaja TIDAK dipakai (dihapus atas permintaan) —
       roket & jejaknya sudah cukup menunjukkan jalur tanpa perlu garis
       statis di belakangnya. */
    .brand_logo-rocket{
      position: absolute;
      top: 50%;
      left: 50%;
      width: 26px;
      height: 26px;
      margin: -13px 0 0 -13px;
      pointer-events: none;
      transform-origin: center center;
      filter: drop-shadow(0 0 6px rgba(46,139,255,.6)) drop-shadow(0 2px 4px rgba(0,0,0,.45));
      animation: brand-rocket-orbit-3d 7s linear infinite;
    }
    .brand_logo-rocket_body{
      display: block;
      width: 100%;
      height: 100%;
    }
    /* Nyala mesin: elemen TERPISAH dari SVG roket (bukan path statis di
       dalam svg) supaya bisa punya animasi sendiri (flicker cepat, tidak
       terikat siklus 7 detik orbit) — menempel di ekor roket dan ikut
       terbawa/berotasi karena ia child dari .brand_logo-rocket yang
       sedang dianimasikan induknya. */
    .brand_logo-rocket_flame{
      position: absolute;
      top: 19px;
      left: 50%;
      width: 7px;
      height: 11px;
      margin-left: -3.5px;
      border-radius: 50% 50% 45% 45%;
      background: linear-gradient(180deg, #fff6b0 0%, #ff9d3d 45%, #ff5a1f 100%);
      filter: drop-shadow(0 0 4px rgba(255,140,40,.85));
      transform-origin: top center;
      animation: brand-flame-flicker .18s ease-in-out infinite alternate;
    }
    @keyframes brand-flame-flicker{
      0%   { transform: scaleY(.7) scaleX(.85);  opacity: .8; }
      50%  { transform: scaleY(1.2) scaleX(1.05); opacity: 1;  }
      100% { transform: scaleY(.9) scaleX(.95);  opacity: .9; }
    }
    /* Jejak percikan di belakang roket: 3 titik kecil yang memakai
       animasi orbit PERSIS SAMA (brand-rocket-orbit-3d) tapi dengan
       animation-delay negatif, sehingga posisinya "ketinggalan" di
       titik yang lebih awal pada siklus yang sama persis — otomatis
       selalu pas di belakang roket di sepanjang jalur, termasuk ikut
       membesar/mengecil & tampil di depan/belakang logo secara
       konsisten dengan roket utamanya (karena rumus & durasinya sama). */
    .brand_logo-trail{
      position: absolute;
      top: 50%;
      left: 50%;
      border-radius: 50%;
      pointer-events: none;
      background: radial-gradient(circle, rgba(140,195,255,.9) 0%, rgba(140,195,255,0) 72%);
      animation-name: brand-rocket-orbit-3d;
      animation-duration: 7s;
      animation-timing-function: linear;
      animation-iteration-count: infinite;
    }
    .brand_logo-trail:nth-child(2){ width: 9px; height: 9px; margin: -4.5px 0 0 -4.5px; opacity: .55; animation-delay: -.12s; }
    .brand_logo-trail:nth-child(3){ width: 6px; height: 6px; margin: -3px 0 0 -3px; opacity: .35; animation-delay: -.28s; }
    .brand_logo-trail:nth-child(4){ width: 4px; height: 4px; margin: -2px 0 0 -2px; opacity: .18; animation-delay: -.48s; }
    /* Rotasi hidung roket dihitung PRESISI dari turunan (tangen) jalur
       elips x=Rx·cosθ, y=Ry·sinθ — bukan pendekatan kasar "θ+180°" yang
       hanya akurat untuk lingkaran sempurna. Untuk elips (Rx≠Ry) arah
       tangennya beda dari itu; rumus eksaknya: φ = atan2(-Rx·sinθ, -Ry·cosθ)
       Nilainya sengaja TIDAK di-mod ke 0–360° tapi dibiarkan naik terus
       (180°→540°) supaya interpolasi antar-keyframe muter satu arah mulus
       tanpa "loncat balik" — totalnya persis 360° dalam satu putaran
       penuh, sesuai fisika: hidung roket menghadap jalur di titik manapun. */
    @keyframes brand-rocket-orbit-3d{
      0%    { transform: translate(100px, 0px)    rotate(180deg)   scale(1);    z-index: 3; }
      8.33% { transform: translate(86.6px, 17px)   rotate(239.53deg) scale(1.18); z-index: 3; }
      16.67%{ transform: translate(50px, 29.4px)   rotate(258.93deg) scale(1.3);  z-index: 3; }
      25%   { transform: translate(0px, 34px)      rotate(270deg)   scale(1.35); z-index: 3; }
      33.33%{ transform: translate(-50px, 29.4px)  rotate(281.07deg) scale(1.3);  z-index: 3; }
      41.67%{ transform: translate(-86.6px, 17px)  rotate(300.47deg) scale(1.18); z-index: 3; }
      50%   { transform: translate(-100px, 0px)    rotate(360deg)   scale(1);    z-index: 1; }
      58.33%{ transform: translate(-86.6px, -17px) rotate(419.53deg) scale(0.83); z-index: 1; }
      66.67%{ transform: translate(-50px, -29.4px) rotate(438.93deg) scale(0.7);  z-index: 1; }
      75%   { transform: translate(0px, -34px)     rotate(450deg)   scale(0.65); z-index: 1; }
      83.33%{ transform: translate(50px, -29.4px)  rotate(461.07deg) scale(0.7);  z-index: 1; }
      91.67%{ transform: translate(86.6px, -17px)  rotate(480.47deg) scale(0.83); z-index: 1; }
      100%  { transform: translate(100px, 0px)     rotate(540deg)   scale(1);    z-index: 3; }
    }
    @media (prefers-reduced-motion: reduce){
      .brand_logo-rocket{ animation: none; opacity: 0; }
      .brand_logo-rocket_flame{ animation: none; }
      .brand_logo-trail{ animation: none; opacity: 0; }
    }

    /* ===== Roket hero — MODE FX (dikendalikan JS: lihat blok script tepat setelah hero) =====
       Animasi CSS di atas (12 keyframe) sengaja DIPERTAHANKAN sebagai CADANGAN: kalau JS
       gagal jalan atau canvas tidak didukung, roket lama tetap mengorbit seperti biasa.
       Begitu JS aktif, ia menambahkan kelas .is-fx pada .brand_logo-wrap → animasi CSS lama
       dimatikan dan JS memegang kendali penuh (gerak mulus, api berlapis, jejak partikel).
       Warna roket memakai class (bukan atribut var()) supaya ada nilai cadangan yang pasti. */
    .brand_logo-rocket .rk-accent{ fill: #2E8BFF; fill: var(--brand-accent, #2E8BFF); }
    .brand_logo-rocket .rk-accent-dark{ fill: #0051B5; fill: var(--brand-light, #0051B5); }
    .brand_logo-rocket_jet{
      display: none;
      position: absolute;
      top: 19.4px;
      left: 50%;
      width: 8px;
      height: 17px;
      margin-left: -4px;
      pointer-events: none;
      transform-origin: 50% 0;
      filter: drop-shadow(0 0 3px rgba(255,150,50,.85));
    }
    .brand_logo-fx{
      position: absolute;
      top: 50%;
      left: 50%;
      display: block;
      max-width: none;
      pointer-events: none;
    }
    .brand_logo-wrap.is-fx .brand_logo-rocket{
      animation: none;
      will-change: transform, opacity;
    }
    .brand_logo-wrap.is-fx .brand_logo-trail,
    .brand_logo-wrap.is-fx .brand_logo-rocket_flame{ display: none; }
    .brand_logo-wrap.is-fx .brand_logo-rocket_jet{ display: block; }
    @media (prefers-reduced-motion: reduce){
      .brand_logo-fx{ display: none; }
    }

    /* ======================================================================
       LIQUID GLASS NAVBAR (khas iOS) — dirombak total: tampilan & logic.
       Di hero (belum discroll): navbar polos, transparan, menempel penuh
       di tepi atas — persis seperti versi sebelumnya. Begitu halaman
       discroll (.is-scrolled), navbar bertransformasi jadi kapsul kaca
       melayang: blur/saturate ala frosted glass, garis highlight tipis di
       tepi atas (efek pantulan cahaya di kaca), menyusut & mengambang.
       Semua properti yang berubah antar dua state ini di-transition CSS
       supaya morph-nya terasa halus/"cair", bukan lompat on/off.
       ====================================================================== */
    .site-nav{
      position: fixed;
      top: 0;
      left: 50%;
      z-index: 1000;
      width: 100%;
      transform: translateX(-50%);
      display: flex;
      border-radius: 0;
      background: transparent;
      -webkit-backdrop-filter: none;
      backdrop-filter: none;
      border: 1px solid transparent;
      box-shadow: none;
      transition: top .5s cubic-bezier(.22,1,.36,1), width .5s cubic-bezier(.22,1,.36,1), border-radius .5s cubic-bezier(.22,1,.36,1), transform .45s cubic-bezier(.22,1,.36,1), background .45s ease, backdrop-filter .45s ease, border-color .45s ease, box-shadow .45s ease;
      will-change: transform;
    }
    .site-nav.is-scrolled{
      top: max(14px, env(safe-area-inset-top));
      width: min(1180px, calc(100% - 24px));
      border-radius: 999px;
      background:
        linear-gradient(180deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,.02) 40%, rgba(255,255,255,.04) 100%),
        rgba(9, 20, 38, .34);
      -webkit-backdrop-filter: blur(42px) saturate(210%) brightness(1.12) contrast(1.05);
      backdrop-filter: blur(42px) saturate(210%) brightness(1.12) contrast(1.05);
      border-color: rgba(255,255,255,.4);
      box-shadow:
        0 22px 54px -14px rgba(0,10,26,.6),
        0 4px 16px -4px rgba(0,10,26,.4),
        inset 0 1.5px 0 rgba(255,255,255,.65),
        inset 0 -1.5px 0 rgba(0,0,0,.22),
        inset 0 0 32px rgba(255,255,255,.08);
    }
    .site-nav.nav-liquid--hidden{
      transform: translateX(-50%) translateY(-140%);
    }
    @media (prefers-reduced-motion: reduce){
      .site-nav{ transition: none; }
    }
    /* Pantulan cahaya di kaca — dua lapis: ::before jadi "rim light" tipis &
       terang di tepi atas kapsul (khas iOS, seolah cahaya menimpa tepi
       kaca), ::after jadi sapuan cahaya diagonal (specular sheen) yang
       melintasi badan kapsul supaya terasa benar-benar seperti permukaan
       kaca melengkung, bukan cuma panel blur polos. Keduanya hanya muncul
       saat liquid glass aktif (sudah discroll). */
    .site-nav::before{
      content: "";
      position: absolute;
      inset: 0;
      border-radius: inherit;
      pointer-events: none;
      background: linear-gradient(180deg, rgba(255,255,255,.55) 0%, rgba(255,255,255,.14) 14%, rgba(255,255,255,0) 45%);
      opacity: 0;
      transition: opacity .4s ease;
    }
    .site-nav::after{
      content: "";
      position: absolute;
      inset: 0;
      border-radius: inherit;
      pointer-events: none;
      background: linear-gradient(115deg, rgba(255,255,255,0) 28%, rgba(255,255,255,.28) 46%, rgba(255,255,255,0) 62%);
      mix-blend-mode: overlay;
      opacity: 0;
      transition: opacity .4s ease;
    }
    .site-nav.is-scrolled::before{
      opacity: 1;
    }
    .site-nav.is-scrolled::after{
      opacity: 1;
    }

    .site-nav_inner{
      position: relative;
      display: flex;
      align-items: center;
      gap: 18px;
      width: 100%;
      padding: 10px 14px 10px 18px;
      transition: padding .4s cubic-bezier(.22,1,.36,1);
    }
    .site-nav.is-scrolled .site-nav_inner{
      padding-top: 7px;
      padding-bottom: 7px;
    }

    .site-nav_brand{
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 0 0 auto;
      text-decoration: none;
      transition: transform .2s cubic-bezier(.22,1,.36,1);
    }
    .site-nav_brand:active{ transform: scale(.96); }
    .site-nav_brand img{
      height: 32px;
      width: auto;
      display: block;
      transition: height .4s cubic-bezier(.22,1,.36,1);
    }
    .site-nav.is-scrolled .site-nav_brand img{ height: 28px; }
    .site-nav_brand span{
      font-family: 'League Spartan', sans-serif;
      color: #fff;
      font-size: 16px;
      line-height: 1;
      font-weight: 800;
      letter-spacing: .01em;
      white-space: nowrap;
      transition: color .2s ease;
    }
    .site-nav_brand:hover span{ color: var(--brand-accent, #2E8BFF); }

    {{-- Menu diubah dari flex:1 1 auto ke flex:0 0 auto (permintaan user):
         supaya menu (Beranda/Layanan/Info) nggak ikut "menggelembung"
         mengisi sisa ruang, melainkan nempel rapat di sebelah kiri
         bahasa+tombol WhatsApp. Sisa ruang kosong sekarang full diserap
         oleh .site-nav_marquee (teks berjalan) di sebelahnya, jadi
         marquee-nya otomatis jadi lebih lebar juga. --}}
    .site-nav_menu{
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 22px;
      list-style: none;
      margin: 0;
      padding: 0;
      flex: 0 0 auto;
      min-width: 0;
    }
    {{-- Setiap <li> juga dijadikan flex container sendiri (bukan cuma isinya)
         supaya link <a> biasa (Home) dan tombol dropdown (<button>) sama-sama
         dipusatkan oleh <li>-nya, bukan cuma oleh dirinya sendiri — ini yang
         sebelumnya bikin teks "Home"/"Beranda" terlihat turun sedikit
         dibanding "Layanan"/"Info", karena <a> & <button> punya model kotak
         bawaan browser yang berbeda. --}}
    .site-nav_menu > li{
      display: flex;
      align-items: center;
    }
    {{-- Item menu biasa (<a>) dan tombol dropdown (<button>) disamakan
         persis: inline-flex + align-items:center + line-height:1 supaya
         baseline teks keduanya sejajar, terlepas dari salah satunya
         punya ikon chevron atau tidak. --}}
    .site-nav_menu > li > a{
      display: inline-flex;
      align-items: center;
      color: rgba(255,255,255,.92);
      font-size: 14px;
      line-height: 1;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .04em;
      text-decoration: none;
      transition: color .2s ease, opacity .2s ease;
    }
    .site-nav_menu > li > a:hover{ color: var(--brand-accent, #2E8BFF); }
    .site-nav_menu > li > a:active{ opacity: .6; }

    .site-nav_cta{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      flex: 0 0 auto;
      margin-left: auto;
      background: linear-gradient(180deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      color: #fff !important;
      font-size: 13.5px;
      line-height: 1;
      font-weight: 700;
      padding: 10px 18px;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,.25);
      box-shadow: 0 6px 16px -4px rgba(46,139,255,.55), inset 0 1px 0 rgba(255,255,255,.35);
      transition: transform .18s cubic-bezier(.22,1,.36,1), box-shadow .18s ease;
    }
    .site-nav_cta:hover{ box-shadow: 0 8px 20px -4px rgba(46,139,255,.7), inset 0 1px 0 rgba(255,255,255,.35); }
    .site-nav_cta:active{ transform: scale(.94); }

    /* Item WhatsApp versi mobile (di dalam dropdown hamburger): tersembunyi
       secara default, cuma dimunculkan di breakpoint mobile di bawah. */
    .site-nav_menu > li.site-nav_cta-item{ display: none; }
    .site-nav_cta-mobile{
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: fit-content;
      max-width: 100%;
      margin: 2px auto 2px;
      background: linear-gradient(180deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      color: #fff !important;
      font-size: 14px;
      font-weight: 700;
      text-transform: none;
      letter-spacing: 0;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,.25);
      box-shadow: 0 6px 16px -4px rgba(46,139,255,.55), inset 0 1px 0 rgba(255,255,255,.35);
      transition: transform .18s cubic-bezier(.22,1,.36,1), box-shadow .18s ease;
    }
    .site-nav_cta-mobile:active{ transform: scale(.94); }

    .site-nav_toggle{
      display: none;
      flex: 0 0 auto;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.16);
      position: relative;
      cursor: pointer;
      transition: background .2s ease, transform .18s cubic-bezier(.22,1,.36,1);
    }
    .site-nav_toggle:active{ transform: scale(.9); }
    .site-nav_toggle span{
      position: absolute;
      left: 9px;
      right: 9px;
      height: 2px;
      background: #fff;
      border-radius: 2px;
      transition: transform .3s cubic-bezier(.22,1,.36,1), opacity .2s ease, top .3s cubic-bezier(.22,1,.36,1);
    }
    .site-nav_toggle span:nth-child(1){ top: 13px; }
    .site-nav_toggle span:nth-child(2){ top: 18px; }
    .site-nav_toggle span:nth-child(3){ top: 23px; }
    .site-nav_toggle.is-open span:nth-child(1){ top: 18px; transform: rotate(45deg); }
    .site-nav_toggle.is-open span:nth-child(2){ opacity: 0; }
    .site-nav_toggle.is-open span:nth-child(3){ top: 18px; transform: rotate(-45deg); }

    @media (max-width: 991px){
      .site-nav_toggle{ display: block; }
      /* Tombol WhatsApp dipindah ke dalam dropdown hamburger di mobile:
         sembunyikan versi navbar-nya, tampilkan versi di dalam menu. */
      .site-nav_cta{ display: none; }
      .site-nav_menu > li.site-nav_cta-item{
        display: block;
        margin-top: 6px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,.1);
      }
      .site-nav_menu{
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        right: 0;
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        gap: 0;
        margin: 0;
        padding: 12px;
        max-height: 0;
        overflow: hidden auto;
        opacity: 0;
        border-radius: 22px;
        /* Liquid glass: blur kuat + latar semi-transparan gelap (bukan
           solid) supaya efek kaca terasa, tapi alpha tetap cukup tinggi
           (.62) supaya kontras teks putih di atasnya tetap terjaga terlepas
           dari seberapa terang konten di belakangnya. */
        background: linear-gradient(180deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,.02) 40%, rgba(255,255,255,.04) 100%), rgba(9, 18, 36, .62);
        -webkit-backdrop-filter: blur(38px) saturate(220%) brightness(1.12) contrast(1.05);
        backdrop-filter: blur(38px) saturate(220%) brightness(1.12) contrast(1.05);
        border: 1px solid rgba(255,255,255,.28);
        box-shadow: 0 20px 46px -12px rgba(0,0,0,.55), inset 0 1.5px 0 rgba(255,255,255,.3), inset 0 -1px 0 rgba(0,0,0,.2), inset 0 0 28px rgba(255,255,255,.06);
        transition: max-height .35s cubic-bezier(.22,1,.36,1), opacity .25s ease;
      }
      .site-nav_menu.is-open{
        max-height: min(70vh, 520px);
        opacity: 1;
      }
      /* PENTING: aturan dasar (di luar media query) mengeset
         `.site-nav_menu > li{ display:flex }` tanpa flex-direction, jadi
         default-nya row (horizontal). Sebelumnya, di mobile cuma
         align-items yang di-override — tombol dropdown ("Solusi"/"Info")
         dan daftar submenu-nya jadi nempel BERSEBELAHAN (bukan bertumpuk
         ke bawah), sehingga teks submenu kepotong di tepi kanan panel.
         flex-direction:column di sini yang jadi kuncinya. */
      .site-nav_menu > li{
        display: flex;
        flex-direction: column;
        align-items: stretch;
      }
      /* Garis pemisah tipis antar-item level atas (Produk/Layanan/Info/
         WhatsApp/bahasa) supaya baris-baris menu nggak menempel satu sama
         lain. Diganti dari nth-child(2)/(3) statis (rawan meleset kalau
         menu "Produk" tidak ada karena belum ada produk aktif) ke selector
         sibling (li + li) yang otomatis mengikuti jumlah item apa pun. Item
         yang sudah punya garis sendiri (tombol WhatsApp) dikecualikan
         supaya tidak dobel. */
      .site-nav_menu > li + li:not(.site-nav_cta-item){
        border-top: 1px solid rgba(255,255,255,.08);
      }
      .site-nav_menu > li > a{
        display: block;
        padding: 14px 16px;
        border-radius: 14px;
        font-size: 14.5px;
        transition: background .18s ease, color .18s ease;
      }
      .site-nav_menu > li > a:active{
        background: rgba(255,255,255,.08);
      }
    }

    /* ===== Navbar running text (marquee) — fade tepi pakai mask, bukan
       gradasi warna solid, supaya tetap benar di atas kaca transparan ===== */
    .site-nav_marquee{
      flex: 1 1 auto;
      min-width: 0;
      overflow: hidden;
      max-width: 480px;
      margin: 0 4px;
      white-space: nowrap;
      position: relative;
      -webkit-mask-image: linear-gradient(to right, transparent 0, #000 24px, #000 calc(100% - 24px), transparent 100%);
      mask-image: linear-gradient(to right, transparent 0, #000 24px, #000 calc(100% - 24px), transparent 100%);
    }
    .site-nav_marquee-track{
      display: inline-block;
      white-space: nowrap;
      padding-left: 100%;
      animation: site-nav-marquee 14s linear infinite;
    }
    .site-nav_marquee-track span{
      display: inline-block;
      padding: 0 40px;
      font-size: 13px;
      font-weight: 600;
      color: #ffd166;
    }
    @keyframes site-nav-marquee{
      0%   { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }
    @media (max-width: 991px){
      .site-nav_marquee{
        display: none;
      }
    }
    @media (max-width: 768px){
      header.vide .vide_content{
        flex-direction: column-reverse;
        text-align: center;
        gap: 24px;
        padding-top: 60px;
        padding-bottom: 60px;
      }
      header.vide .brand{
        align-items: center !important;
        text-align: center !important;
      }
      header.vide .hero-text{
        text-align: center;
      }
    }

    /* ===== ABOUT / WHO WE ARE =====
       Split 2 kolom full-bleed: foto kiri, panel navy solid kanan.
       Sengaja polos: satu warna latar, satu warna aksen, garis tipis
       sebagai pemisah, dan angka kecil sebagai penanda poin. */
    .about-split{
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: stretch;
    }
    .about-split_col--photo{
      position: relative;
      overflow: hidden;
      min-height: 480px;
    }
    .about-split_col--photo img{
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .about-split_col--text{
      background: var(--brand-dark, #00224C);
      display: flex;
      align-items: center;
    }
    .about-split_inner{
      width: 100%;
      max-width: 580px;
      margin: 0 auto;
      padding: 88px 56px;
    }
    .about-split_eyebrow{
      display: flex;
      align-items: center;
      gap: 14px;
      text-transform: uppercase;
      letter-spacing: .18em;
      font-weight: 600;
      font-size: 12.5px;
      color: var(--brand-accent, #2E8BFF);
      margin-bottom: 22px;
    }
    .about-split_eyebrow::before{
      content: "";
      width: 34px;
      height: 1px;
      background: currentColor;
    }
    .about-split_title{
      text-align: left;
      margin: 0 0 22px;
      font-family: 'League Spartan', sans-serif;
      font-weight: 700;
      font-size: clamp(26px, 3.2vw, 38px);
      line-height: 1.2;
      letter-spacing: 0;
      color: #fff;
    }
    .about-split_desc{
      color: rgba(255,255,255,.72);
      font-size: 15.5px;
      line-height: 1.8;
      margin: 0 0 34px;
    }
    .about-split_points{
      list-style: none;
      margin: 0 0 40px;
      padding: 0;
      border-top: 1px solid rgba(255,255,255,.14);
    }
    .about-split_points li{
      display: flex;
      align-items: baseline;
      gap: 18px;
      padding: 15px 0;
      border-bottom: 1px solid rgba(255,255,255,.14);
      color: #fff;
      font-size: 15px;
      line-height: 1.5;
    }
    .about-split_points li span:first-child{
      flex: 0 0 auto;
      font-size: 12.5px;
      font-weight: 600;
      letter-spacing: .08em;
      color: var(--brand-accent, #2E8BFF);
      font-variant-numeric: tabular-nums;
    }
    .about-split_cta{
      text-align: center;
    }
    @media (max-width: 900px){
      .about-split{
        grid-template-columns: 1fr;
      }
      .about-split_col--photo{
        min-height: 280px;
      }
      .about-split_inner{
        padding: 56px 24px;
      }
    }

    /* ===== Partner marquee: geser mulus per-logo lalu berhenti sejenak, loop presisi tanpa skip ===== */
    @php
      $partnerTotal   = $partners->count();
      $rightCount     = (int) ceil($partnerTotal / 2);
      $leftCount      = (int) floor($partnerTotal / 2);
      // porsi waktu per logo: sebagian untuk geser (move), sisanya untuk diam (hold)
      $moveFraction   = 0.55; // 55% dari waktu tiap step dipakai untuk geser, 45% untuk diam
      $rightDuration  = max(8, $rightCount * 1.8);
      $leftDuration   = max(8, $leftCount * 1.8);
    @endphp
    @if($rightCount > 0)
    .partner-marquee--right .partner-marquee_track {
      animation: partner-marquee-right {{ $rightDuration }}s ease-in-out infinite;
    }
    @keyframes partner-marquee-right {
      @for($s = 0; $s <= $rightCount; $s++)
        @php
          // posisi pecahan eksak (bukan dibulatkan) supaya step terakhir jatuh TEPAT di -50%
          $posPct     = -50 * ($s / $rightCount);
          $stepStart  = ($s / $rightCount) * 100;
          $stepMoveEnd = $s < $rightCount
            ? $stepStart + (100 / $rightCount) * $moveFraction
            : $stepStart;
        @endphp
        {{ round($stepStart, 4) }}% { transform: translateX({{ round($posPct, 6) }}%); }
        @if($s < $rightCount)
          {{ round($stepMoveEnd, 4) }}% { transform: translateX({{ round(-50 * (($s+1) / $rightCount), 6) }}%); }
        @endif
      @endfor
    }
    @endif
    @if($leftCount > 0)
    .partner-marquee--left .partner-marquee_track {
      animation: partner-marquee-left {{ $leftDuration }}s ease-in-out infinite;
    }
    @keyframes partner-marquee-left {
      @for($s = 0; $s <= $leftCount; $s++)
        @php
          $posPct      = -50 + 50 * ($s / $leftCount);
          $stepStart   = ($s / $leftCount) * 100;
          $stepMoveEnd = $s < $leftCount
            ? $stepStart + (100 / $leftCount) * $moveFraction
            : $stepStart;
        @endphp
        {{ round($stepStart, 4) }}% { transform: translateX({{ round($posPct, 6) }}%); }
        @if($s < $leftCount)
          {{ round($stepMoveEnd, 4) }}% { transform: translateX({{ round(-50 + 50 * (($s+1) / $leftCount), 6) }}%); }
        @endif
      @endfor
    }
    @endif

    /* Footer columns dipindah ke public/css/style.css (dipakai bersama semua halaman) */

    /* ===== FAQ list, 2 kolom (kreasiai.com style) ===== */
    .faq-section .container.center + .container{
      margin-top: 40px;
    }
    .faq-list{
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      column-gap: 60px;
      align-items: start;
    }
    .faq-item{
      border-bottom: 1px solid #e6e8ec;
      background: transparent;
    }
    .faq-item summary{
      list-style: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 18px 4px;
      font-size: 15px;
      font-weight: 400;
      color: #2b2d33;
      text-transform: none;
      transition: color .2s ease;
    }
    .faq-item summary:hover{
      color: var(--brand-accent);
    }
    .faq-item summary::-webkit-details-marker{
      display: none;
    }
    .faq-item summary i{
      color: #b7bcc6;
      font-size: 13px;
      transition: transform .25s ease;
      flex-shrink: 0;
    }
    .faq-item[open] summary i{
      transform: rotate(90deg);
    }
    .faq-answer{
      padding: 0 4px 18px;
    }
    .faq-answer p{
      margin: 0;
      font-size: 14px;
      line-height: 24px;
      color: #808289;
    }
    @media (max-width: 767px){
      .faq-list{
        grid-template-columns: 1fr;
        column-gap: 0;
      }
    }
    @media (max-width: 479px){
      .faq-item summary{
        padding: 14px 2px;
        font-size: 14px;
      }
      .faq-answer{
        padding: 0 2px 14px;
      }
    }
    /* ===== Navbar dropdown groups — kaca melayang, senada dengan navbar ===== */
    .site-nav_menu .has-dropdown{
      position: relative;
    }
    .dropdown-toggle{
      background: none;
      border: none;
      color: rgba(255,255,255,.92);
      font-family: inherit;
      font-size: 14px;
      line-height: 1;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      padding: 0;
    }
    .dropdown-toggle:hover{
      color: var(--brand-accent);
    }
    .dropdown-toggle:active{
      opacity: .6;
    }
    .dropdown-toggle i{
      font-size: 11px;
      line-height: 1;
      transition: transform .2s ease;
    }
    .has-dropdown:hover .dropdown-toggle i,
    .has-dropdown.is-open .dropdown-toggle i{
      transform: rotate(180deg);
    }
    .dropdown-menu{
      list-style: none;
      margin: 0;
      padding: 10px;
      position: absolute;
      top: calc(100% + 12px);
      left: 50%;
      min-width: 190px;
      background: linear-gradient(180deg, rgba(255,255,255,.1) 0%, rgba(255,255,255,0) 50%), rgba(9,16,32,.42);
      -webkit-backdrop-filter: blur(34px) saturate(200%) brightness(1.1);
      backdrop-filter: blur(34px) saturate(200%) brightness(1.1);
      border: 1px solid rgba(255,255,255,.22);
      border-radius: 16px;
      box-shadow: 0 16px 34px -10px rgba(0,0,0,.5), inset 0 1.5px 0 rgba(255,255,255,.32), inset 0 -1px 0 rgba(0,0,0,.16);
      opacity: 0;
      visibility: hidden;
      transform: translateX(-50%) translateY(6px) scale(.96);
      transition: opacity .22s ease, transform .3s cubic-bezier(.22,1,.36,1), visibility .22s ease;
      z-index: 20;
    }
    .dropdown-menu li + li{
      margin-top: 2px;
    }
    .dropdown-menu a{
      display: block;
      border-radius: 10px;
      padding: 10px 14px;
      color: rgba(255,255,255,.82);
      font-size: 14px;
      font-weight: 400;
      text-transform: none;
      letter-spacing: 0;
      white-space: nowrap;
      transition: background .18s ease, color .18s ease;
    }
    .dropdown-menu a::after{
      display: none;
    }
    .dropdown-menu a:hover{
      color: #fff;
      background: rgba(255,255,255,.1);
    }
    .dropdown-menu a:active{
      background: rgba(255,255,255,.18);
    }
    .has-dropdown:hover .dropdown-menu,
    .has-dropdown.is-open .dropdown-menu{
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(0) scale(1);
    }
    /* Dropdown "Layanan" — tiap item punya ikon bulat + judul + subtitle
       (2 baris), jadi butuh lebar & layout flex sendiri (dropdown "Info"
       tetap 1 baris polos seperti sebelumnya). */
    .dropdown-menu--services{ min-width: 300px; padding: 8px; }
    .dropdown-menu--services li + li{ margin-top: 4px; }
    .dropdown-menu--services a{
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 12px;
      white-space: normal;
    }
    .dropdown-menu--services .dropdown-menu_icon{
      flex: 0 0 auto;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      background: linear-gradient(180deg, rgba(46,139,255,.28), rgba(46,139,255,.1));
      border: 1px solid rgba(255,255,255,.16);
      color: var(--brand-accent, #2E8BFF);
      font-size: 15px;
      transition: background .18s ease, color .18s ease;
    }
    .dropdown-menu--services a:hover .dropdown-menu_icon{
      background: linear-gradient(180deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      color: #fff;
    }
    .dropdown-menu--services .dropdown-menu_text{ min-width: 0; }
    .dropdown-menu--services .dropdown-menu_title{
      display: block;
      color: rgba(255,255,255,.94);
      font-size: 14px;
      font-weight: 600;
      line-height: 1.3;
      transition: color .18s ease;
    }
    .dropdown-menu--services a:hover .dropdown-menu_title{ color: #fff; }
    .dropdown-menu--services .dropdown-menu_subtitle{
      display: block;
      margin-top: 2px;
      color: rgba(255,255,255,.55);
      font-size: 12px;
      font-weight: 400;
      line-height: 1.4;
      white-space: normal;
    }
    @media (max-width: 991px){
      .site-nav_menu .has-dropdown{
        width: 100%;
      }
      .dropdown-toggle{
        width: 100%;
        padding: 14px 16px;
        border-radius: 14px;
        font-size: 14.5px;
        justify-content: space-between;
        transition: background .18s ease;
      }
      .has-dropdown.is-open .dropdown-toggle{
        background: rgba(255,255,255,.06);
        border-radius: 14px 14px 0 0;
      }
      .dropdown-toggle:active{
        background: rgba(255,255,255,.08);
      }
      .dropdown-menu{
        position: static;
        transform: none;
        opacity: 1;
        visibility: visible;
        box-shadow: none;
        background: transparent;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        border: none;
        border-radius: 0;
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height .3s cubic-bezier(.22,1,.36,1);
      }
      .has-dropdown.is-open .dropdown-menu{
        max-height: 340px;
        overflow-y: auto;
        /* Tanpa baris ini, transform dari versi desktop menang lewat urutan
           CSS dan mendorong seluruh submenu keluar layar ke kiri saat dibuka
           di mobile — itu sebabnya teks dropdown terlihat hilang. */
        transform: none;
        /* Panel kecil di dalam panel besar: latar gelap semi-transparan
           (lebih gelap dari panel induk) supaya sub-item (mis. daftar
           layanan) tetap terlihat jelas sebagai "laci" tersendiri walau
           kedua panel sama-sama kaca/blur — bukan latar solid, jadi nuansa
           liquid glass tetap kerasa. */
        background: rgba(0,0,0,.3);
        border-radius: 0 0 14px 14px;
        margin: 0 0 8px;
        padding: 6px;
      }
      .dropdown-menu a{
        padding: 11px 14px 11px 16px;
        font-size: 13.5px;
        border-radius: 10px;
      }
      /* Reset min-width:300px dari versi desktop supaya submenu tidak
         berpotensi lebih lebar dari layar di HP yang sangat sempit. */
      .dropdown-menu--services{
        min-width: 0;
      }
      /* Versi mobile dropdown "Layanan": ikon lebih kecil, subtitle tetap
         turun ke baris baru, indent kiri disamakan dengan item lain. */
      .dropdown-menu--services a{ padding: 10px 14px 10px 16px; gap: 10px; }
      .dropdown-menu--services .dropdown-menu_icon{ width: 30px; height: 30px; font-size: 13px; border-radius: 8px; }
      .dropdown-menu--services .dropdown-menu_title{ font-size: 13.5px; }
      .dropdown-menu--services .dropdown-menu_subtitle{ font-size: 11.5px; }
    }

    /* ===== Language switcher (dropdown, tampilan bendera SVG) ===== */
    .site-nav_utility{
      display: flex;
      align-items: center;
      gap: 14px;
      flex: 0 0 auto;
    }
    .site-nav_lang{
      position: relative;
    }
    .lang-toggle{
      display: flex;
      align-items: center;
      gap: 7px;
      background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.18);
      color: #FFF;
      font-family: inherit;
      text-transform: none;
      padding: 6px 10px;
      border-radius: 30px;
      cursor: pointer;
      line-height: 1;
      transition: background .2s ease, transform .18s cubic-bezier(.22,1,.36,1);
    }
    .lang-toggle:hover{
      background: rgba(255,255,255,.18);
    }
    .lang-toggle:active{
      transform: scale(.93);
    }
    .lang-toggle i{
      font-size: 9px;
      opacity: .8;
    }
    .lang-flag-icon{
      display: block;
      width: 22px;
      height: 15px;
      border-radius: 3px;
      overflow: hidden;
      box-shadow: 0 0 0 1px rgba(255,255,255,.3);
      flex-shrink: 0;
    }
    .lang-flag-icon svg{
      display: block;
      width: 100%;
      height: 100%;
    }
    #langSwitcher .dropdown-menu.lang-menu{
      left: auto;
      right: 0;
      min-width: 140px;
      padding: 8px;
      transform: translateY(6px) scale(.96);
    }
    #langSwitcher.is-open .dropdown-menu.lang-menu,
    #langSwitcher:hover .dropdown-menu.lang-menu{
      transform: translateY(0) scale(1);
    }
    .lang-menu li + li{
      margin-top: 4px !important;
    }
    .lang-option{
      display: flex;
      align-items: center;
      gap: 8px;
      width: 100%;
      height: 32px;
      background: none;
      border: 2px solid transparent;
      border-radius: 6px;
      padding: 0 10px 0 6px;
      cursor: pointer;
    }
    .lang-option:hover{
      background: rgba(255,255,255,.06);
    }
    .lang-option.is-active{
      border-color: var(--brand-accent);
      background: rgba(255,255,255,.06);
    }
    .lang-option-label{
      color: #fff;
      font-size: 12px;
      line-height: 1;
      white-space: nowrap;
    }
    @media (max-width: 991px){
      .site-nav_utility{
        gap: 10px;
        margin-left: auto;
      }
      .lang-toggle{
        padding: 5px 8px;
      }
      .lang-flag-icon{
        width: 20px;
        height: 14px;
      }
    }

    /* ===== Language switcher dipindah ke dalam dropdown hamburger (mobile
       saja). #navLangSlot adalah slot <li> kosong di akhir #navMenu; JS di
       bawah memindahkan #langSwitcher ke situ saat lebar layar <= 991px, dan
       mengembalikannya ke .site-nav_utility saat tampilan desktop — jadi
       markup & tampilan desktop sama sekali tidak berubah. ===== */
    #navLangSlot:empty{
      display: none;
    }
    @media (max-width: 991px){
      #navLangSlot{
        border-top: 1px solid rgba(255,255,255,.1);
        margin-top: 6px;
        padding-top: 8px;
      }
      #navLangSlot .lang-toggle{
        width: 100%;
        background: none;
        border: none;
        border-radius: 14px;
        padding: 12px 16px;
        justify-content: space-between;
      }
      #navLangSlot .lang-toggle:hover{
        background: rgba(255,255,255,.08);
      }
      #langSwitcher .dropdown-menu.lang-menu{
        position: static;
        left: auto;
        right: auto;
        transform: none;
        min-width: 0;
        width: 100%;
        padding: 0;
        box-shadow: none;
        background: transparent;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        border: none;
        border-radius: 0;
        max-height: 0;
        overflow: hidden;
        opacity: 1;
        visibility: visible;
        transition: max-height .25s ease;
      }
      #langSwitcher.is-open .dropdown-menu.lang-menu{
        max-height: 200px;
      }
      #navLangSlot .lang-option{
        width: 100%;
        height: auto;
        border-radius: 0;
        padding: 12px 40px;
      }
    }

    /* ===== Blog / Artikel Terbaru (landing page) ===== */
    .blog-section{
      background: #ffffff;
    }
    .blog-section h2{
      color: var(--brand-dark);
    }
    .blog-section hr{
      border-color: rgba(0, 34, 76, 0.15);
    }
    .blog-section .container.center + .container{
      margin-top: 0;
    }
    .blog-section_grid{
      margin-top: 46px;
    }
    .blog-card{
      display: block;
      background: #fff;
      border: 1px solid #edeef4;
      border-radius: 16px;
      overflow: hidden;
      height: 100%;
      transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s ease;
    }
    .blog-card:hover{
      transform: translateY(-6px);
      box-shadow: 0 20px 36px -18px rgba(0, 34, 76, 0.28);
    }
    .blog-card_thumb{
      aspect-ratio: 16/9;
      background: linear-gradient(135deg, var(--brand-light), var(--brand-dark));
      overflow: hidden;
    }
    .blog-card_thumb img{
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .4s cubic-bezier(.22,1,.36,1);
    }
    .blog-card:hover .blog-card_thumb img{
      transform: scale(1.08);
    }
    .blog-card_body{
      padding: 22px;
    }
    .blog-card_meta{
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .blog-card_date{
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: var(--brand-light);
    }
    .blog-card_likes{
      display: inline-flex;
      align-items: center;
      gap: 4px;
      flex-shrink: 0;
      font-size: 12.5px;
      font-weight: 700;
      color: #9aa5b5;
    }
    .blog-card_likes svg{
      color: var(--brand-accent);
    }
    .blog-card_title{
      font-size: 18px;
      line-height: 1.4;
      margin: 10px 0 8px;
      color: #1a2233;
      text-transform: none;
      letter-spacing: normal;
      font-weight: 700;
      transition: color .2s ease;
    }
    .blog-card:hover .blog-card_title{
      color: var(--brand-light);
    }
    .blog-card_excerpt{
      font-size: 14px;
      line-height: 1.6;
      color: #6b7280;
      margin: 0 0 14px;
    }
    .blog-card_readmore{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: var(--brand-accent);
      transition: gap .2s ease;
    }
    .blog-card:hover .blog-card_readmore{
      gap: 10px;
    }
    .btn-sm{
      padding: 11px 22px;
      font-size: 14px;
      letter-spacing: .04em;
    }
    @media (max-width: 767px){
      .blog-section_grid .grid_4 + .grid_4{
        margin-top: 28px;
      }
    }

    /* =========================================================
       TESTIMONIALS — redesain
       Bug lama: elemen <svg class="testimonial-card_quote-icon">
       dan <blockquote class="testimonial-card"> sama sekali tidak
       punya CSS sendiri, jadi browser memakai ukuran default SVG
       (mengikuti viewBox, jadi raksasa) + margin bawaan <blockquote>.
       Hasilnya tanda kutip abu-abu besar menutupi separuh card,
       persis seperti di screenshot bug.
       Sekarang: ikon kutip jadi badge kecil bergradasi, teks quote
       dikasih ukuran wajar, dan seluruh card dirapikan jadi gaya
       modern/futuristik (glow tipis, kartu kaca, ring foto gradasi)
       yang senada dengan warna brand (--brand-accent/--brand-light). */
    .testimonial-section{
      position: relative;
      overflow: hidden;
      padding-top: 90px;
      padding-bottom: 100px;
      background:
        radial-gradient(560px 320px at 12% 0%, rgba(46,139,255,.08), transparent 60%),
        radial-gradient(620px 360px at 88% 100%, rgba(0,81,181,.07), transparent 60%),
        #f7f9fd;
    }
    .testimonial-section::before{
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(46,139,255,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(46,139,255,.05) 1px, transparent 1px);
      background-size: 34px 34px;
      -webkit-mask-image: radial-gradient(60% 60% at 50% 20%, #000 0%, transparent 75%);
      mask-image: radial-gradient(60% 60% at 50% 20%, #000 0%, transparent 75%);
      pointer-events: none;
    }
    .testimonial-section .container{ position: relative; z-index: 1; }

    /* Rapatkan jarak subtitle -> card. Sebelumnya bertumpuk:
       margin global "* + .owl-carousel" (49px) + padding atas item (10px).
       Di-override khusus testimoni supaya carousel lain tidak ikut berubah. */
    .testimonial-section .testimonial-carousel{
      margin-top: 22px;
    }
    .testimonial-carousel .item{
      padding: 0 12px 26px;
      box-sizing: border-box;
    }
    .testimonial-card{
      position: relative;
      display: flex;
      flex-direction: column;
      height: 100%;
      margin: 0;
      padding: 34px 28px 26px;
      box-sizing: border-box;
      text-align: left;
      background: #fff;
      border: 1px solid #eceff5;
      border-radius: 20px;
      box-shadow: 0 14px 34px -18px rgba(16,32,64,.18);
      overflow: hidden;
      transition: transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s ease, border-color .35s ease;
    }
    .testimonial-card::after{
      /* Tanda kutip dekoratif — sekarang dikunci ukurannya lewat font-size,
         dibuat sangat transparan, dan overflow:hidden pada parent memastikan
         dia tidak pernah "bocor" keluar/menutupi konten seperti bug awal. */
      content: "\201C";
      position: absolute;
      top: -34px;
      right: 6px;
      font-family: Georgia, 'Times New Roman', serif;
      font-size: 150px;
      line-height: 1;
      font-weight: 700;
      color: rgba(46,139,255,.07);
      pointer-events: none;
      z-index: 0;
    }
    .testimonial-card:hover{
      transform: translateY(-8px);
      border-color: rgba(46,139,255,.35);
      box-shadow: 0 22px 44px -16px rgba(16,32,64,.26);
    }
    .testimonial-card_quote-icon{
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      width: 42px;
      height: 42px;
      padding: 11px;
      box-sizing: border-box;
      border-radius: 13px;
      background: linear-gradient(135deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      box-shadow: 0 10px 20px -8px rgba(46,139,255,.6);
      color: #fff;
      margin-bottom: 20px;
    }
    .testimonial-card_quote{
      position: relative;
      z-index: 1;
      flex: 1 0 auto;
      margin: 0 0 26px;
      font-size: 15px;
      line-height: 1.75;
      color: #4b5567;
      font-weight: 400;
    }
    .testimonial-card_footer{
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      gap: 14px;
      margin-top: auto;
      padding-top: 20px;
      border-top: 1px solid #eef1f7;
    }
    .testimonial-card_photo{
      width: 50px;
      height: 50px;
      flex-shrink: 0;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid transparent;
      background:
        linear-gradient(#fff,#fff) padding-box,
        linear-gradient(135deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5)) border-box;
    }
    .testimonial-card_meta{
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    .testimonial-card_name{
      font-style: normal;
      font-family: 'League Spartan', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: #16213a;
      line-height: 1.3;
    }
    .testimonial-card_role{
      margin: 2px 0 0;
      font-size: 12.5px;
      color: #8a93a3;
      line-height: 1.3;
    }
    .testimonial-card_company{
      margin: 1px 0 0;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--brand-accent, #2E8BFF);
      line-height: 1.3;
    }

    /* ---- Navigasi testimoni ----------------------------------------
       Dulu: 1 titik per testimoni (bisa belasan titik -> baris panjang).
       Sekarang:
        - Testimoni > 3  : pager kapsul kaca yang ukurannya TETAP, berapa
                           pun jumlah testimoninya: [<]  01 ━━━ 08  [>]
        - Testimoni <= 3 : titik kecil bulat (maksimal 3, jadi tetap rapi).
       Pager dibuat di Blade (di bawah carousel), perilakunya diikat di
       public/js/script.js. */
    .testimonial-carousel .owl-dots{
      display: flex; justify-content: center; align-items: center;
      gap: 10px; margin-top: 4px; line-height: 1;
    }
    .testimonial-carousel .owl-dots .owl-dot{
      display: block; width: auto; height: auto; margin: 0; padding: 4px;
      border: 0; border-radius: 0; background: none; cursor: pointer;
    }
    .testimonial-carousel .owl-dots .owl-dot + .owl-dot{ margin-left: 0; }
    .testimonial-carousel .owl-dots .owl-dot:hover,
    .testimonial-carousel .owl-dots .owl-dot.active{ background: none; }
    .testimonial-carousel .owl-dot span{
      display: block; width: 8px; height: 8px; border-radius: 50%;
      background: rgba(0,34,76,.18);
      transition: transform .3s cubic-bezier(.22,1,.36,1), background .3s ease, box-shadow .3s ease;
    }
    .testimonial-carousel .owl-dot.active span{
      background: linear-gradient(135deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      box-shadow: 0 0 0 4px rgba(46,139,255,.18);
      transform: scale(1.15);
    }

    .testimonial-pager{
      display: flex; align-items: center; justify-content: center; gap: 14px;
      width: max-content; max-width: 100%; margin: 6px auto 0; padding: 6px;
      box-sizing: border-box; border-radius: 999px;
      background: rgba(255,255,255,.78);
      -webkit-backdrop-filter: blur(14px) saturate(160%); backdrop-filter: blur(14px) saturate(160%);
      border: 1px solid rgba(46,139,255,.18);
      box-shadow: 0 14px 30px -16px rgba(16,32,64,.35), inset 0 1px 0 rgba(255,255,255,.9);
    }
    .testimonial-pager .tp-btn{
      display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto;
      width: 38px; height: 38px; padding: 0; border: 0; border-radius: 50%;
      background: #fff; color: var(--brand-light, #0051B5); cursor: pointer;
      box-shadow: inset 0 0 0 1px #e3e9f4;
      transition: background .25s ease, color .25s ease, transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s ease;
    }
    .testimonial-pager .tp-btn:hover{
      background: linear-gradient(135deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      color: #fff; transform: scale(1.06);
      box-shadow: 0 8px 18px -8px rgba(46,139,255,.7);
    }
    .testimonial-pager .tp-btn:active{ transform: scale(.94); }
    .testimonial-pager .tp-btn:focus-visible{ outline: 2px solid var(--brand-accent, #2E8BFF); outline-offset: 2px; }
    .testimonial-pager .tp-btn svg{ display: block; }
    .testimonial-pager .tp-status{
      display: flex; align-items: center; gap: 10px;
      font-family: 'League Spartan', sans-serif; font-size: 14px; font-weight: 700;
      line-height: 1; color: #16213a; font-variant-numeric: tabular-nums;
    }
    .testimonial-pager .tp-total{ color: #9aa5b8; font-weight: 600; }
    .testimonial-pager .tp-track{
      position: relative; display: block; width: 84px; height: 4px;
      border-radius: 99px; background: rgba(0,34,76,.1); overflow: hidden;
    }
    .testimonial-pager .tp-bar{
      position: absolute; top: 0; bottom: 0; left: 0; display: block; border-radius: inherit;
      background: linear-gradient(90deg, var(--brand-accent, #2E8BFF), var(--brand-light, #0051B5));
      transition: width .45s cubic-bezier(.22,1,.36,1);
    }
    @media (max-width: 480px){
      .testimonial-pager{ gap: 10px; }
      .testimonial-pager .tp-track{ width: 60px; }
    }
    @media (prefers-reduced-motion: reduce){
      .testimonial-pager .tp-bar, .testimonial-pager .tp-btn, .testimonial-carousel .owl-dot span{ transition: none; }
    }
  </style>
</head>

<body>
{{-- ======================== NAVBAR ======================== --}}
{{-- Sengaja diletakkan sebagai sibling langsung dari <body> (di LUAR
     div.page), bukan di dalamnya. Kalau nav ada di dalam elemen yang
     punya transform/filter/perspective di CSS lain (mis. untuk animasi
     transisi halaman), position:fixed pada nav akan jadi relatif ke
     elemen itu alih-alih ke viewport — akibatnya navbar ikut ke-scroll,
     bukan diam. Menaruhnya di luar div.page menghindari masalah ini
     sama sekali, apa pun isi CSS ancestor-nya. --}}
<nav class="site-nav" id="site-nav">
    <div class="container site-nav_inner">
      <a href="#" class="site-nav_brand">
        <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}" alt="{{ $setting->site_title }}"/>
        <span>{{ $setting->site_title }}</span>
      </a>
      @if($setting->promo_enabled && $setting->promo_text)
      <div class="site-nav_marquee" aria-label="Promo">
        <div class="site-nav_marquee-track">
          <span>@bi($setting, 'promo_text')</span>
          <span>@bi($setting, 'promo_text')</span>
        </div>
      </div>
      @endif

      <ul class="site-nav_menu" id="navMenu">
        @include('partials.nav-products')
        <li class="has-dropdown">
          <button type="button" class="dropdown-toggle"><span data-i18n="nav.servicesToggle">Solusi</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></button>
          <ul class="dropdown-menu dropdown-menu--services">
            <li>
              <a href="{{ route('solusi.website') }}">
                <span class="dropdown-menu_icon"><i class="fa fa-desktop" aria-hidden="true"></i></span>
                <span class="dropdown-menu_text">
                  <span class="dropdown-menu_title" data-i18n="nav.servicesWebsite">Jasa Pembuatan Website</span>
                  <span class="dropdown-menu_subtitle" data-i18n="nav.servicesWebsiteDesc">Website profesional & responsif sesuai kebutuhan bisnis Anda</span>
                </span>
              </a>
            </li>
            <li>
              <a href="{{ route('solusi.app-mobile') }}">
                <span class="dropdown-menu_icon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                <span class="dropdown-menu_text">
                  <span class="dropdown-menu_title" data-i18n="nav.servicesAppMobile">Jasa Pembuatan App Mobile</span>
                  <span class="dropdown-menu_subtitle" data-i18n="nav.servicesAppMobileDesc">Aplikasi Android & iOS yang disesuaikan dengan kebutuhan bisnis</span>
                </span>
              </a>
            </li>
            <li>
              <a href="{{ route('solusi.custom-system') }}">
                <span class="dropdown-menu_icon"><i class="fa fa-cogs" aria-hidden="true"></i></span>
                <span class="dropdown-menu_text">
                  <span class="dropdown-menu_title" data-i18n="nav.servicesCustomSystem">Jasa Pembuatan Sistem Kustom</span>
                  <span class="dropdown-menu_subtitle" data-i18n="nav.servicesCustomSystemDesc">Sistem/software kustom untuk mengotomasi proses bisnis Anda</span>
                </span>
              </a>
            </li>
          </ul>
        </li>
        <li class="has-dropdown">
          <button type="button" class="dropdown-toggle"><span data-i18n="nav.infoToggle">Info</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></button>
          <ul class="dropdown-menu">
            <li><a href="{{ route('blog.index') }}" data-i18n="nav.blog">Blog</a></li>
            <li><a href="{{ route('portfolio.index') }}" data-i18n="nav.portfolio">Portofolio</a></li>
            <li><a href="{{ route('about') }}" data-i18n="nav.about">Tentang Kami</a></li>
            <li><a href="{{ route('contact') }}" data-i18n="nav.contact">Kontak</a></li>
          </ul>
        </li>
        {{-- Slot kosong: JS di bawah memindahkan #langSwitcher ke sini saat
             tampilan mobile, supaya ganti bahasa jadi bagian dari dropdown
             hamburger. Kosong = tersembunyi otomatis (di desktop). --}}
        <li class="site-nav_lang-item" id="navLangSlot"></li>
        {{-- Tombol WhatsApp versi mobile: item ini hanya tampil di dalam
             dropdown hamburger (≤991px). Tombol WhatsApp asli di
             .site-nav_utility disembunyikan di lebar itu supaya navbar
             mobile lebih ringkas — lihat @media (max-width: 991px) di atas. --}}
        <li class="site-nav_cta-item">
          <a href="https://wa.me/{{ $setting->whatsapp_number }}?text={{ urlencode($setting->whatsapp_message) }}"
             target="_blank" rel="noopener" class="site-nav_cta-mobile">
            <svg viewBox="0 0 32 32" width="18" height="18" fill="currentColor" style="flex-shrink:0;">
              <path d="M16.001 3C9.373 3 4 8.373 4 15c0 2.386.7 4.607 1.906 6.475L4 29l7.72-1.867A11.94 11.94 0 0 0 16.001 27C22.628 27 28 21.627 28 15S22.628 3 16.001 3zm0 21.818a9.77 9.77 0 0 1-4.98-1.363l-.357-.212-4.583 1.108 1.127-4.47-.233-.367A9.78 9.78 0 0 1 6.182 15c0-5.415 4.404-9.818 9.819-9.818S25.818 9.585 25.818 15 21.415 24.818 16.001 24.818zm5.396-7.34c-.296-.148-1.75-.864-2.021-.963-.271-.099-.469-.148-.667.148-.198.296-.766.963-.939 1.161-.173.198-.346.222-.642.074-.296-.148-1.249-.46-2.379-1.467-.879-.784-1.472-1.753-1.645-2.049-.173-.296-.018-.456.13-.604.134-.133.296-.346.444-.519.148-.173.198-.297.296-.494.099-.198.05-.371-.025-.519-.074-.148-.667-1.607-.914-2.202-.24-.577-.485-.499-.667-.508l-.568-.01c-.198 0-.519.074-.79.371-.271.297-1.037 1.014-1.037 2.472s1.062 2.868 1.21 3.066c.148.198 2.089 3.19 5.062 4.474.707.305 1.259.487 1.689.623.71.226 1.355.194 1.866.118.569-.085 1.75-.716 1.997-1.407.247-.692.247-1.284.173-1.407-.074-.123-.271-.198-.568-.346z"/>
            </svg>
            <span data-i18n="nav.whatsappCta">WhatsApp Kami</span>
          </a>
        </li>
      </ul>

      <div class="site-nav_utility">
        {{-- Language switcher: dropdown, tampilan bendera SVG (bukan emoji, supaya tampil konsisten di semua OS/browser) --}}
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
                <span class="lang-option-label" aria-hidden="true">Indonesia</span>
                <span class="lang-flag-icon" aria-hidden="true">
                  <svg viewBox="0 0 3 2" xmlns="http://www.w3.org/2000/svg"><rect width="3" height="1" fill="#CE1126"/><rect y="1" width="3" height="1" fill="#FFFFFF"/></svg>
                </span>
              </button>
            </li>
            <li role="presentation">
              <button type="button" class="lang-option" data-lang="en" role="option" aria-checked="false" aria-label="English">
                <span class="lang-option-label" aria-hidden="true">English</span>
                <span class="lang-flag-icon" aria-hidden="true">
                  <svg viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                    <rect width="60" height="30" fill="#00247d"/>
                    <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                    <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="2"/>
                    <path d="M30,0 V30 M0,15 H60" stroke="#fff" stroke-width="10"/>
                    <path d="M30,0 V30 M0,15 H60" stroke="#cf142b" stroke-width="6"/>
                  </svg>
                </span>
              </button>
            </li>
          </ul>
        </div>

        <button class="site-nav_toggle" id="navToggle" data-i18n-aria-label="nav.toggleLabel" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
      </div>

      <a href="https://wa.me/{{ $setting->whatsapp_number }}?text={{ urlencode($setting->whatsapp_message) }}"
         target="_blank" rel="noopener" class="site-nav_cta">
        <svg viewBox="0 0 32 32" width="18" height="18" fill="currentColor" style="flex-shrink:0;">
          <path d="M16.001 3C9.373 3 4 8.373 4 15c0 2.386.7 4.607 1.906 6.475L4 29l7.72-1.867A11.94 11.94 0 0 0 16.001 27C22.628 27 28 21.627 28 15S22.628 3 16.001 3zm0 21.818a9.77 9.77 0 0 1-4.98-1.363l-.357-.212-4.583 1.108 1.127-4.47-.233-.367A9.78 9.78 0 0 1 6.182 15c0-5.415 4.404-9.818 9.819-9.818S25.818 9.585 25.818 15 21.415 24.818 16.001 24.818zm5.396-7.34c-.296-.148-1.75-.864-2.021-.963-.271-.099-.469-.148-.667.148-.198.296-.766.963-.939 1.161-.173.198-.346.222-.642.074-.296-.148-1.249-.46-2.379-1.467-.879-.784-1.472-1.753-1.645-2.049-.173-.296-.018-.456.13-.604.134-.133.296-.346.444-.519.148-.173.198-.297.296-.494.099-.198.05-.371-.025-.519-.074-.148-.667-1.607-.914-2.202-.24-.577-.485-.499-.667-.508l-.568-.01c-.198 0-.519.074-.79.371-.271.297-1.037 1.014-1.037 2.472s1.062 2.868 1.21 3.066c.148.198 2.089 3.19 5.062 4.474.707.305 1.259.487 1.689.623.71.226 1.355.194 1.866.118.569-.085 1.75-.716 1.997-1.407.247-.692.247-1.284.173-1.407-.074-.123-.271-.198-.568-.346z"/>
        </svg>
        <span data-i18n="nav.whatsappCta">WhatsApp Kami</span>
      </a>
    </div>
</nav>

<div class="page">
  {{-- ======================== HEADER ======================== --}}
  @php
    $heroVideoIsWebm = $setting->hero_video && \Illuminate\Support\Str::endsWith(strtolower($setting->hero_video), '.webm');
  @endphp
  <header class="vide" id="home">
    <video class="hero-bg-video" autoplay muted loop playsinline preload="auto"
           poster="{{ asset('video/video-bg.jpg') }}">
      @if($setting->hero_video)
        <source src="{{ asset('storage/'.$setting->hero_video) }}" type="{{ $heroVideoIsWebm ? 'video/webm' : 'video/mp4' }}">
      @else
        <source src="{{ asset('video/video-bg.mp4') }}" type="video/mp4">
        <source src="{{ asset('video/video-bg.webm') }}" type="video/webm">
        <source src="{{ asset('video/video-bg.ogv') }}" type="video/ogg">
      @endif
    </video>
    <div class="container vide_content" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:40px;">
      <div class="hero-text hero-fade-in" style="flex:1 1 320px;align-self:center;max-width:560px;">
        <h2 class="hero-fade-in_item" style="margin:0 0 16px 0;"><span data-i18n-lang="id">{{ $setting->hero_title }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->hero_title_en ?: $setting->hero_title }}</span></h2>
        <h3 class="hero-fade-in_item" style="margin:0 0 20px 0;"><span data-i18n-lang="id">{{ $setting->hero_subtitle }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->hero_subtitle_en ?: $setting->hero_subtitle }}</span></h3>

        <a href="https://wa.me/{{ $setting->whatsapp_number }}?text={{ urlencode($setting->whatsapp_message) }}"
           target="_blank" rel="noopener" class="btn btn-whatsapp hero-fade-in_item" style="display:inline-flex;align-items:center;gap:8px;font-size:17px;padding:16px 28px;margin-top:0;">
          <svg viewBox="0 0 32 32" width="30" height="30" fill="currentColor" style="vertical-align:middle;flex-shrink:0;">
            <path d="M16.001 3C9.373 3 4 8.373 4 15c0 2.386.7 4.607 1.906 6.475L4 29l7.72-1.867A11.94 11.94 0 0 0 16.001 27C22.628 27 28 21.627 28 15S22.628 3 16.001 3zm0 21.818a9.77 9.77 0 0 1-4.98-1.363l-.357-.212-4.583 1.108 1.127-4.47-.233-.367A9.78 9.78 0 0 1 6.182 15c0-5.415 4.404-9.818 9.819-9.818S25.818 9.585 25.818 15 21.415 24.818 16.001 24.818zm5.396-7.34c-.296-.148-1.75-.864-2.021-.963-.271-.099-.469-.148-.667.148-.198.296-.766.963-.939 1.161-.173.198-.346.222-.642.074-.296-.148-1.249-.46-2.379-1.467-.879-.784-1.472-1.753-1.645-2.049-.173-.296-.018-.456.13-.604.134-.133.296-.346.444-.519.148-.173.198-.297.296-.494.099-.198.05-.371-.025-.519-.074-.148-.667-1.607-.914-2.202-.24-.577-.485-.499-.667-.508l-.568-.01c-.198 0-.519.074-.79.371-.271.297-1.037 1.014-1.037 2.472s1.062 2.868 1.21 3.066c.148.198 2.089 3.19 5.062 4.474.707.305 1.259.487 1.689.623.71.226 1.355.194 1.866.118.569-.085 1.75-.716 1.997-1.407.247-.692.247-1.284.173-1.407-.074-.123-.271-.198-.568-.346z"/>
          </svg>
          <span data-i18n="hero.ctaWhatsapp">Hubungi Kami</span>
        </a>
      </div>

      <div class="brand hero-fade-in_item" style="flex:0 1 280px;text-align:right;display:flex;flex-direction:column;align-items:flex-end;align-self:center;animation-delay:.5s;">
        <div class="brand_logo-wrap">
          @if($setting->logo && \Illuminate\Support\Str::endsWith($setting->logo, ['.mp4', '.webm']))
            <video src="{{ asset('storage/'.$setting->logo) }}" class="brand_logo-video" autoplay muted loop playsinline></video>
          @else
            <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}" alt="{{ $setting->site_title }}"/>
          @endif
          <span class="brand_logo-trail" aria-hidden="true"></span>
          <span class="brand_logo-trail" aria-hidden="true"></span>
          <span class="brand_logo-trail" aria-hidden="true"></span>
          <span class="brand_logo-rocket" aria-hidden="true">
            <svg class="brand_logo-rocket_body" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <defs>
                <linearGradient id="brandRkBody" x1="0" y1="0" x2="1" y2="0">
                  <stop offset="0" stop-color="#b4c3da"/>
                  <stop offset=".42" stop-color="#ffffff"/>
                  <stop offset="1" stop-color="#93a6c4"/>
                </linearGradient>
                <radialGradient id="brandRkGlass" cx=".35" cy=".3" r=".9">
                  <stop offset="0" stop-color="#9fe0ff"/>
                  <stop offset=".55" stop-color="#2b6fd6"/>
                  <stop offset="1" stop-color="#0a2a5e"/>
                </radialGradient>
              </defs>
              <path class="rk-accent" d="M8.1 11.6 L3.9 17.7 L4.6 19.7 L8.1 17.4 Z"/>
              <path class="rk-accent" d="M15.9 11.6 L20.1 17.7 L19.4 19.7 L15.9 17.4 Z"/>
              <path class="rk-accent-dark" d="M8.1 14.6 L5.6 18.3 L4.6 19.7 L8.1 17.4 Z" opacity=".55"/>
              <path class="rk-accent-dark" d="M15.9 14.6 L18.4 18.3 L19.4 19.7 L15.9 17.4 Z" opacity=".55"/>
              <path d="M9.5 16.4 L14.5 16.4 L13.7 18.8 L10.3 18.8 Z" fill="#56657f"/>
              <path d="M12 1.1 C14.9 3.5 16.2 7.3 16 12.4 L15.7 16.8 L8.3 16.8 L8 12.4 C7.8 7.3 9.1 3.5 12 1.1 Z" fill="url(#brandRkBody)"/>
              <path class="rk-accent" d="M12 1.1 C13.3 2.2 14.3 3.6 14.9 5.4 L9.1 5.4 C9.7 3.6 10.7 2.2 12 1.1 Z"/>
              <rect class="rk-accent" x="8.05" y="13.5" width="7.9" height=".85"/>
              <circle cx="12" cy="9.5" r="2.3" fill="#e4eefc"/>
              <circle cx="12" cy="9.5" r="1.75" fill="url(#brandRkGlass)"/>
              <ellipse cx="11.25" cy="8.75" rx=".6" ry=".34" fill="#ffffff" opacity=".9" transform="rotate(-35 11.25 8.75)"/>
            </svg>
            <span class="brand_logo-rocket_flame"></span>
            <svg class="brand_logo-rocket_jet" viewBox="0 0 10 20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <defs>
                <linearGradient id="brandRkFlameOuter" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stop-color="#ffb347"/>
                  <stop offset=".55" stop-color="#ff5a1f" stop-opacity=".85"/>
                  <stop offset="1" stop-color="#ff3d00" stop-opacity="0"/>
                </linearGradient>
                <linearGradient id="brandRkFlameCore" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stop-color="#ffffff"/>
                  <stop offset=".5" stop-color="#fff2a8"/>
                  <stop offset="1" stop-color="#ffd25a" stop-opacity="0"/>
                </linearGradient>
              </defs>
              <path d="M1 0 C.4 6 2.6 12 5 20 C7.4 12 9.6 6 9 0 Z" fill="url(#brandRkFlameOuter)"/>
              <path d="M2.6 0 C2.2 4.5 3.6 8.5 5 13 C6.4 8.5 7.8 4.5 7.4 0 Z" fill="url(#brandRkFlameCore)"/>
            </svg>
          </span>
        </div>
        <h1 class="brand_name" style="margin:0 0 6px 0;font-family:'League Spartan', sans-serif;">
          <a href="./" style="font-family:'League Spartan', sans-serif;">{{ $setting->site_title }}</a>
        </h1>
        <p class="brand_slogan" style="margin:0;">{{ $setting->brand_slogan }}</p>
      </div>
    </div>
  </header>

  {{-- Mesin animasi roket hero. Sengaja diletakkan tepat setelah hero (bukan di akhir body)
       supaya langsung aktif sebelum script berat lain selesai dimuat. --}}
<script>
/* ==========================================================================
   ROKET HERO — mesin animasi berbasis JS (requestAnimationFrame)

   Kenapa JS, bukan CSS keyframe lagi?
   - Versi CSS lama hanya punya 12 titik → jalurnya "poligon" bersegi, rotasi
     dan skala diinterpolasi linear antar-titik → terasa kaku/patah-patah.
     Di sini posisi dihitung LANGSUNG dari rumus elips di setiap frame, jadi
     jalurnya benar-benar mulus di semua resolusi & kecepatan layar.
   - Arah hidung roket = turunan (tangen) rumus jalur itu sendiri, jadi selalu
     pas menghadap arah terbang, tanpa "loncat" di titik manapun.
   - Jejak asap/api berupa partikel di ruang dunia (bukan titik yang menempel
     di roket) → ekornya melengkung mengikuti orbit dan memudar alami.

   Efek yang ada:
   1. Orbit 3D  : elips miring; sisi dekat (bawah) roket membesar & lewat DI
                  DEPAN logo, sisi jauh (atas) mengecil & lewat DI BELAKANG logo.
                  Roket juga memendek sedikit saat menuju/menjauhi kamera.
   2. Kecepatan : ala hukum Kepler — ngebut di sisi dekat, santai di sisi jauh,
                  plus goyangan kecil (bob & roll) supaya terasa hidup.
   3. Mesin     : api berlapis yang berkedip acak, panjangnya ikut kecepatan.
   4. Jejak     : partikel api → biru brand + kilau bintang berkelap-kelip.
                  Partikel yang lahir di sisi jauh digambar di belakang logo.
   5. Interaksi : arahkan kursor ke logo = mesin menderu; klik/tap = turbo
                  (ngebut, semburan percikan, cincin kejut).
   6. Intro     : roket meluncur keluar dari balik logo saat hero muncul.

   Aman & hemat: berhenti otomatis saat hero di luar layar / tab tidak aktif,
   menghormati prefers-reduced-motion, kualitas turun otomatis di perangkat
   lambat, dan animasi CSS lama tetap jadi cadangan kalau JS/canvas gagal.
   ========================================================================== */
(function () {
  'use strict';

  var wrap   = document.querySelector('.brand_logo-wrap');
  var rocket = wrap && wrap.querySelector('.brand_logo-rocket');
  var jet    = wrap && wrap.querySelector('.brand_logo-rocket_jet');
  if (!wrap || !rocket || !jet) { return; }
  if (!window.requestAnimationFrame || !document.createElement('canvas').getContext) { return; }

  /* ---------- Parameter ---------- */
  var TAU = Math.PI * 2;
  var PERIOD = 7;                     // detik per putaran (rata-rata, sama seperti versi lama)
  var ECC = 0.26;                     // 0 = kecepatan konstan; makin besar makin beda dekat vs jauh
  var RATIO = 0.34;                   // Ry / Rx (sama seperti orbit lama: 34 / 100)
  var TILT = -11 * Math.PI / 180;     // kemiringan orbit (ujung kanan sedikit naik)
  var COS_T = Math.cos(TILT), SIN_T = Math.sin(TILT);
  var W0 = TAU / (PERIOD * Math.sqrt(1 - ECC * ECC));   // kecepatan sudut dasar (rad/dtk)
  var INTRO_DELAY = 0.5;              // selaras dengan delay fade-in blok logo (.5s)
  var INTRO_DUR = 1.5;
  var MAX_PARTS = 200;                // batas partikel per lapisan
  var NOZZLE = 7.6;                   // jarak lubang mesin dari pusat roket (px, skala 1)

  /* ---------- State ---------- */
  var Rx = 100, Ry = Rx * RATIO;      // radius orbit (Rx dikecilkan otomatis di layar sempit)
  var theta = 3 * Math.PI / 2;        // mulai di puncak orbit (sisi jauh, tersembunyi di balik logo)
  var clock = 0, last = null, raf = 0;
  var running = false, inView = true, reduced = false, inited = false, failed = false;
  var hover = false, hoverV = 0, clickV = 0;
  var quality = 1, ema = 1 / 60;
  var emitAcc = 0, prev = { x: 0, y: 0, ok: false };
  var cur = { x: 0, y: 0, s: 1 };
  var lastFront = null;
  var rings = [];
  var pool = [];
  var view = { ready: false, left: -160, hh: 86, cw: 320, ch: 172, dpr: 1 };
  var layers = null, SPR = null, STAR = null;
  var SPR_N = 20;

  /* ---------- Sprite (digambar sekali, dipakai ulang → murah) ---------- */
  var PAL = [[255, 247, 205], [255, 196, 96], [255, 140, 60], [205, 226, 255], [110, 175, 255], [46, 139, 255]];
  var PAL_AT = [0, 0.15, 0.32, 0.55, 0.8, 1];

  function palColor(t) {
    for (var i = 1; i < PAL_AT.length; i++) {
      if (t <= PAL_AT[i]) {
        var u = (t - PAL_AT[i - 1]) / (PAL_AT[i] - PAL_AT[i - 1]);
        var a = PAL[i - 1], b = PAL[i];
        return [a[0] + (b[0] - a[0]) * u, a[1] + (b[1] - a[1]) * u, a[2] + (b[2] - a[2]) * u];
      }
    }
    return PAL[PAL.length - 1];
  }

  function buildSprites() {
    var i, c, g, col, rgb, grad;
    SPR = [];
    for (i = 0; i < SPR_N; i++) {
      c = document.createElement('canvas');
      c.width = c.height = 40;
      g = c.getContext('2d');
      col = palColor(i / (SPR_N - 1));
      rgb = Math.round(col[0]) + ',' + Math.round(col[1]) + ',' + Math.round(col[2]);
      grad = g.createRadialGradient(20, 20, 0, 20, 20, 20);
      grad.addColorStop(0, 'rgba(' + rgb + ',1)');
      grad.addColorStop(0.35, 'rgba(' + rgb + ',0.55)');
      grad.addColorStop(1, 'rgba(' + rgb + ',0)');
      g.fillStyle = grad;
      g.fillRect(0, 0, 40, 40);
      SPR.push(c);
    }
    // Kilau bintang 4 sudut (glow lembut + dua garis tipis)
    c = document.createElement('canvas');
    c.width = c.height = 48;
    g = c.getContext('2d');
    grad = g.createRadialGradient(24, 24, 0, 24, 24, 12);
    grad.addColorStop(0, 'rgba(255,255,255,0.95)');
    grad.addColorStop(1, 'rgba(160,205,255,0)');
    g.fillStyle = grad;
    g.fillRect(0, 0, 48, 48);
    g.translate(24, 24);
    g.fillStyle = 'rgba(235,245,255,0.98)';
    g.beginPath();
    g.moveTo(0, -23);
    g.quadraticCurveTo(1.6, -1.6, 23, 0);
    g.quadraticCurveTo(1.6, 1.6, 0, 23);
    g.quadraticCurveTo(-1.6, 1.6, -23, 0);
    g.quadraticCurveTo(-1.6, -1.6, 0, -23);
    g.closePath();
    g.fill();
    STAR = c;
  }

  /* ---------- Lapisan kanvas: belakang (di bawah logo) & depan (di atas logo) ---------- */
  function makeLayer(z) {
    var c = document.createElement('canvas');
    c.className = 'brand_logo-fx';
    c.setAttribute('aria-hidden', 'true');
    c.style.zIndex = z;
    wrap.insertBefore(c, rocket);          // di depan roket dalam DOM → roket menimpa partikel
    var g = c.getContext('2d');
    return g ? { canvas: c, ctx: g, list: [] } : null;
  }

  function ensureBuilt() {
    if (inited) { return true; }
    var back = makeLayer(1), front = makeLayer(3);
    if (!back || !front) {
      if (back) { wrap.removeChild(back.canvas); }
      if (front) { wrap.removeChild(front.canvas); }
      return false;
    }
    layers = { back: back, front: front };
    buildSprites();
    inited = true;
    return true;
  }

  /* ---------- Ukuran adaptif ----------
     Di layar sempit orbit tidak boleh keluar dari layar, jadi radius dikecilkan
     (minimum 84 px = tetap melewati sisi logo). Kanvas juga dipotong sebatas
     lebar layar supaya tidak pernah memicu scroll horizontal. */
  function layout() {
    if (!inited) { return; }
    var r = wrap.getBoundingClientRect();
    var vw = document.documentElement.clientWidth || window.innerWidth || 0;
    var cx = r.width ? r.left + r.width / 2 : vw / 2;     // belum ada ukuran → anggap di tengah
    var avail = vw > 0 ? Math.min(cx, vw - cx) : 100;
    Rx = Math.max(84, Math.min(100, avail - 24));
    Ry = Rx * RATIO;

    var hw = Rx + 60, hh = 86;
    var left = vw > 0 ? Math.max(-hw, -cx) : -hw;
    var right = vw > 0 ? Math.min(hw, vw - cx) : hw;
    var cw = Math.max(40, Math.round(right - left));
    var ch = hh * 2;
    var dpr = Math.min(2, window.devicePixelRatio || 1);

    if (view.ready && view.left === left && view.hh === hh && view.cw === cw && view.dpr === dpr) {
      return;                                              // tidak berubah → jangan reset kanvas
    }
    view.ready = true;
    view.left = left; view.hh = hh; view.cw = cw; view.ch = ch; view.dpr = dpr;
    [layers.back, layers.front].forEach(function (L) {
      var c = L.canvas;
      c.width = Math.round(cw * dpr);
      c.height = Math.round(ch * dpr);
      c.style.width = cw + 'px';
      c.style.height = ch + 'px';
      c.style.marginLeft = left + 'px';
      c.style.marginTop = (-hh) + 'px';
      L.ctx.setTransform(dpr, 0, 0, dpr, -left * dpr, hh * dpr);   // koordinat = px dari pusat logo
    });
  }

  var layoutQueued = false;
  function queueLayout() {
    if (layoutQueued) { return; }
    layoutQueued = true;
    window.requestAnimationFrame(function () { layoutQueued = false; layout(); });
  }

  /* ---------- Partikel ---------- */
  function spawn(layer, kind, x, y, vx, vy, size, life, alpha, drag, grow) {
    var list = layer.list;
    if (list.length >= MAX_PARTS) { return; }
    var p = pool.pop() || {};
    p.kind = kind; p.x = x; p.y = y; p.vx = vx; p.vy = vy;
    p.s0 = size; p.life = life; p.a0 = alpha; p.drag = drag; p.grow = grow;
    p.age = 0; p.tw = 9 + Math.random() * 7; p.ph = Math.random() * TAU;
    list.push(p);
  }

  function stepLayer(L, dt) {
    var a = L.list, i, p, k;
    for (i = a.length - 1; i >= 0; i--) {
      p = a[i];
      p.age += dt;
      if (p.age >= p.life) {
        pool.push(p);
        a[i] = a[a.length - 1];
        a.pop();
        continue;
      }
      k = Math.exp(-p.drag * dt);
      p.vx *= k; p.vy *= k;
      p.x += p.vx * dt; p.y += p.vy * dt;
    }
  }

  function drawLayer(L, dim, withRings) {
    var g = L.ctx, a = L.list, i, p, t, s, al, tw;
    g.globalCompositeOperation = 'source-over';
    g.globalAlpha = 1;
    g.clearRect(view.left, -view.hh, view.cw, view.ch);
    g.globalCompositeOperation = 'lighter';
    for (i = 0; i < a.length; i++) {
      p = a[i];
      t = p.age / p.life;
      if (p.kind === 0) {
        al = p.a0 * Math.pow(1 - t, 1.35) * (t < 0.08 ? t / 0.08 : 1) * dim;
        s = p.s0 * (1 + p.grow * t);
        g.globalAlpha = al;
        g.drawImage(SPR[Math.min(SPR_N - 1, (t * SPR_N) | 0)], p.x - s, p.y - s, s * 2, s * 2);
      } else {
        tw = 0.55 + 0.45 * Math.sin(p.age * p.tw + p.ph);
        al = p.a0 * (1 - t) * tw * dim;
        s = p.s0 * (0.55 + 0.45 * tw);
        g.globalAlpha = al;
        g.drawImage(STAR, p.x - s, p.y - s, s * 2, s * 2);
      }
    }
    if (withRings && rings.length) {
      g.lineWidth = 1.4;
      for (i = 0; i < rings.length; i++) {
        t = rings[i].age / rings[i].life;
        g.globalAlpha = Math.pow(1 - t, 2) * 0.75;
        g.strokeStyle = 'rgb(150,205,255)';
        g.beginPath();
        g.arc(rings[i].x, rings[i].y, 8 + t * 46, 0, TAU);
        g.stroke();
      }
    }
    g.globalAlpha = 1;
  }

  /* ---------- Turbo (klik / tap pada logo) ---------- */
  function burst() {
    if (!running) { return; }
    var i, ang, sp;
    clickV = 1;
    rings.push({ x: cur.x, y: cur.y, age: 0, life: 0.7 });
    for (i = 0; i < 26; i++) {
      ang = Math.random() * TAU;
      sp = 50 + Math.random() * 110;
      spawn(layers.front, 0, cur.x, cur.y, Math.cos(ang) * sp, Math.sin(ang) * sp,
            1.6 + Math.random() * 1.8, 0.45 + Math.random() * 0.4, 0.75, 2.6, 0.5);
    }
    for (i = 0; i < 9; i++) {
      ang = Math.random() * TAU;
      sp = 25 + Math.random() * 70;
      spawn(layers.front, 1, cur.x, cur.y, Math.cos(ang) * sp, Math.sin(ang) * sp,
            3 + Math.random() * 3, 0.9 + Math.random() * 0.7, 0.95, 1.6, 0);
    }
  }

  /* ---------- Satu langkah simulasi + render ---------- */
  function frame(now) {
    raf = 0;
    if (!running) { return; }
    try {
      if (last === null) { last = now; }
      var dt = (now - last) / 1000;
      last = now;
      if (!(dt > 0)) { dt = 1 / 60; }
      if (dt > 0.05) { dt = 0.05; }              // tab baru aktif lagi → jangan lompat
      ema += (dt - ema) * 0.05;
      quality = ema > 0.028 ? 0.5 : 1;           // perangkat lambat → partikel dikurangi setengah
      clock += dt;

      /* -- Intro & dorongan mesin -- */
      var e = clock - INTRO_DELAY;
      var intro = e <= 0 ? 0 : Math.min(1, e / INTRO_DUR);
      var introEase = 1 - Math.pow(1 - intro, 3);
      hoverV += ((hover ? 0.55 : 0) - hoverV) * (1 - Math.exp(-7 * dt));
      clickV = Math.max(0, clickV - dt / 1.7);
      var boost = Math.min(1.4, hoverV + clickV + (intro < 1 ? Math.pow(1 - intro, 2) * 0.9 : 0));

      /* -- Gerak di orbit -- */
      theta += W0 * (1 + ECC * Math.sin(theta)) * (1 + 1.35 * boost) * dt;
      if (theta >= TAU) { theta -= TAU; }
      var ct = Math.cos(theta), st = Math.sin(theta);
      var lx = Rx * ct, ly = Ry * st;
      var px = lx * COS_T - ly * SIN_T;
      var py = lx * SIN_T + ly * COS_T + Math.sin(clock * 2.4) * 1.3;          // bob halus
      var dx = -Rx * st, dy = Ry * ct;                                          // turunan jalur = arah terbang
      var hx = dx * COS_T - dy * SIN_T, hy = dx * SIN_T + dy * COS_T;
      var hl = Math.sqrt(hx * hx + hy * hy) || 1;
      hx /= hl; hy /= hl;
      var rot = Math.atan2(hx, -hy)                                             // hidung (atas SVG) → arah terbang
              + (Math.sin(clock * 3.1) * 3.2 + Math.sin(clock * 1.3 + 1) * 1.6) * Math.PI / 180;

      /* -- Kedalaman 3D: st > 0 = sisi dekat kamera -- */
      var s = (1 + 0.36 * st) * (0.4 + 0.6 * introEase);
      var f = Math.sqrt(st * st + RATIO * RATIO * ct * ct);                     // pemendekan saat menuju/menjauhi kamera
      var sx = s * (0.97 + 0.03 * Math.cos(clock * 2.6));
      var sy = s * (0.82 + 0.18 * f);
      var front = st >= 0;
      if (front !== lastFront) { rocket.style.zIndex = front ? 3 : 1; lastFront = front; }

      rocket.style.opacity = (Math.min(1, intro * 3) * (0.82 + 0.09 * (st + 1))).toFixed(3);
      rocket.style.transform = 'translate3d(' + px.toFixed(2) + 'px,' + py.toFixed(2) + 'px,0) rotate(' +
                               rot.toFixed(4) + 'rad) scale(' + sx.toFixed(3) + ',' + sy.toFixed(3) + ')';
      cur.x = px; cur.y = py; cur.s = s;

      /* -- Api mesin: berlapis, berkedip acak, memanjang saat boost -- */
      var n = (Math.sin(clock * 57) + Math.sin(clock * 39 + 2.1) + Math.sin(clock * 91 + 4.2)) / 3;
      jet.style.transform = 'scale(' + ((0.92 - 0.1 * n) * (1 + 0.25 * boost)).toFixed(3) + ',' +
                            ((0.88 + 0.28 * n) * (1 + 1.15 * boost)).toFixed(3) + ')';
      jet.style.opacity = (0.9 + 0.1 * n).toFixed(3);

      /* -- Jejak partikel (dilahirkan di lubang mesin, dirata sepanjang gerak frame ini) -- */
      var bx = -Math.sin(rot), by = Math.cos(rot);                              // arah "belakang" roket
      var nx = px + bx * NOZZLE * sy, ny = py + by * NOZZLE * sy;
      if (intro > 0.04) {
        if (!prev.ok) { prev.x = nx; prev.y = ny; prev.ok = true; }
        emitAcc += (58 + 150 * boost) * quality * dt;
        var cnt = Math.floor(emitAcc), i, u, ex, ey, back, layer, spark;
        emitAcc -= cnt;
        layer = front ? layers.front : layers.back;
        for (i = 1; i <= cnt; i++) {
          u = i / cnt;
          ex = prev.x + (nx - prev.x) * u;
          ey = prev.y + (ny - prev.y) * u;
          back = (14 + Math.random() * 22) * (1 + 0.5 * boost);
          spark = Math.random() < 0.11 + 0.2 * boost;
          if (spark) {
            spawn(layer, 1, ex, ey, bx * back * 0.4 + (Math.random() - 0.5) * 28, by * back * 0.4 + (Math.random() - 0.5) * 28,
                  (2.4 + Math.random() * 2.4) * s, 1 + Math.random() * 0.7, 0.95, 1.2, 0);
          } else {
            spawn(layer, 0, ex, ey, bx * back + (Math.random() - 0.5) * 18, by * back + (Math.random() - 0.5) * 18,
                  (2.6 + Math.random() * 2.2) * s, 0.55 + Math.random() * 0.5 + 0.25 * boost, 0.55, 1.8, 1.6 + Math.random() * 0.8);
          }
        }
      }
      prev.x = nx; prev.y = ny;

      /* -- Cincin kejut -- */
      for (i = rings.length - 1; i >= 0; i--) {
        rings[i].age += dt;
        if (rings[i].age >= rings[i].life) { rings.splice(i, 1); }
      }

      /* -- Gambar -- */
      stepLayer(layers.back, dt);
      stepLayer(layers.front, dt);
      drawLayer(layers.back, 0.8, false);       // sedikit lebih redup = terasa lebih jauh
      drawLayer(layers.front, 1, true);

      raf = window.requestAnimationFrame(frame);
    } catch (err) {
      fail();
    }
  }

  /* ---------- Kendali hidup-mati ---------- */
  function start() {
    if (running || failed) { return; }
    if (!ensureBuilt()) { fail(); return; }
    layout();
    wrap.classList.add('is-fx');
    running = true;
    last = null;
    prev.ok = false;
    raf = window.requestAnimationFrame(frame);
  }

  function stop() {
    running = false;
    if (raf) { window.cancelAnimationFrame(raf); raf = 0; }
  }

  // Kalau ada error tak terduga: bersihkan semua, biarkan animasi CSS lama mengambil alih.
  function fail() {
    failed = true;
    stop();
    wrap.classList.remove('is-fx');
    rocket.style.opacity = '';
    rocket.style.transform = '';
    rocket.style.zIndex = '';
    jet.style.transform = '';
    jet.style.opacity = '';
    if (layers) {
      [layers.back, layers.front].forEach(function (L) {
        if (L.canvas.parentNode) { L.canvas.parentNode.removeChild(L.canvas); }
      });
    }
  }

  function sync() {
    var should = inView && !document.hidden && !reduced && !failed;
    if (should && !running) { start(); }
    else if (!should && running) { stop(); }
    if (reduced) {
      wrap.classList.remove('is-fx');           // CSS reduced-motion menyembunyikan roket
    }
  }

  /* ---------- Pasang ---------- */
  var mq = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
  reduced = !!(mq && mq.matches);
  if (mq) {
    var onMq = function () { reduced = !!mq.matches; sync(); };
    if (mq.addEventListener) { mq.addEventListener('change', onMq); }
    else if (mq.addListener) { mq.addListener(onMq); }
  }

  if (!reduced) {
    // Sembunyikan roket sampai frame pertama menaruhnya di posisi yang benar
    // (mencegah kedipan sesaat di tengah logo).
    rocket.style.opacity = '0';
  }

  var pe = !!window.PointerEvent;
  wrap.addEventListener(pe ? 'pointerenter' : 'mouseenter', function () { hover = true; }, false);
  wrap.addEventListener(pe ? 'pointerleave' : 'mouseleave', function () { hover = false; }, false);
  wrap.addEventListener(pe ? 'pointerdown' : 'mousedown', burst, false);

  window.addEventListener('resize', queueLayout, false);
  window.addEventListener('orientationchange', queueLayout, false);
  window.addEventListener('load', queueLayout, false);
  document.addEventListener('visibilitychange', sync, false);

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      inView = entries[entries.length - 1].isIntersecting;
      sync();
    }, { threshold: 0 }).observe(wrap);
  }

  sync();
})();
</script>

  {{-- ======================== CONTENT ======================== --}}
  <main>
    {{-- CLIENTS / PARTNERS --}}
    @if($partners->count())
    <section class="well2 center">
      <div class="container wow fadeInUp" data-wow-duration="0.9s">
        <h2 class="section-heading" data-i18n="clients.title">Clients</h2>
        <hr class="section-heading_bar"/>
        <p data-i18n="clients.subtitle">Telah dipercaya oleh berbagai bisnis</p>
      </div>
      <div class="partner-marquee-wrap">
        <div class="partner-marquee partner-marquee--right">
          <ul class="partner-marquee_track">
            @foreach($partners as $i => $partner)
              @if($i % 2 === 0)
                <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
              @endif
            @endforeach
            @foreach($partners as $i => $partner)
              @if($i % 2 === 0)
                <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
              @endif
            @endforeach
          </ul>
        </div>
        @if($partners->count() > 1)
        <div class="partner-marquee partner-marquee--left">
          <ul class="partner-marquee_track">
            @foreach($partners as $i => $partner)
              @if($i % 2 === 1)
                <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
              @endif
            @endforeach
            @foreach($partners as $i => $partner)
              @if($i % 2 === 1)
                <li><img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}"/></li>
              @endif
            @endforeach
          </ul>
        </div>
        @endif
      </div>
    </section>
    @endif

    {{-- TESTIMONIALS --}}
    @if($testimonials->count())
    <section class="well testimonial-section">
      <div class="container center">
        <div class="wow fadeInUp" data-wow-duration="0.9s">
          <h2 class="section-heading" data-i18n="testimonials.title">testimonials</h2>
          <hr class="section-heading_bar"/>
          <p data-i18n="testimonials.subtitle">Apa kata mereka yang sudah menggunakan layanan kami</p>
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.15s" data-wow-duration="0.9s">
          @foreach($testimonials as $t)
          <div class="item">
            <blockquote class="testimonial-card">
              <svg class="testimonial-card_quote-icon" viewBox="0 0 32 24" fill="none" aria-hidden="true">
                <path d="M8.8 24c-2.32 0-4.24-.8-5.76-2.4C1.52 20 .76 17.973.76 15.52c0-2.187.573-4.36 1.72-6.52C3.653 6.813 5.32 4.813 7.52 3l3.6 3.24c-1.573 1.28-2.787 2.573-3.64 3.88-.827 1.28-1.293 2.44-1.4 3.48.267-.08.573-.12.92-.12 1.44 0 2.64.507 3.6 1.52.987 1.013 1.48 2.267 1.48 3.76 0 1.52-.507 2.787-1.52 3.8C9.547 23.507 8.32 24 8.8 24zm17.04 0c-2.32 0-4.24-.8-5.76-2.4-1.52-1.6-2.28-3.627-2.28-6.08 0-2.187.573-4.36 1.72-6.52 1.173-2.187 2.84-4.187 5-6l3.6 3.24c-1.573 1.28-2.787 2.573-3.64 3.88-.827 1.28-1.293 2.44-1.4 3.48.267-.08.573-.12.92-.12 1.44 0 2.64.507 3.6 1.52.987 1.013 1.48 2.267 1.48 3.76 0 1.52-.507 2.787-1.52 3.8-.987 1.013-2.213 1.44-3.72 1.44z" fill="currentColor"/>
              </svg>
              <p class="testimonial-card_quote">@bi($t, 'message', ['nl2br' => true])</p>
              <div class="testimonial-card_footer">
                <img class="testimonial-card_photo" src="{{ $t->photo ? asset('storage/'.$t->photo) : asset('images/page-1_img13.jpg') }}" alt="{{ $t->name }}"/>
                <div class="testimonial-card_meta">
                  <cite class="testimonial-card_name">{{ $t->name }}</cite>
                  @if($t->title)
                  <p class="testimonial-card_role">@bi($t, 'title')</p>
                  @endif
                  @if($t->company)
                  <p class="testimonial-card_company">{{ $t->company }}</p>
                  @endif
                </div>
              </div>
            </blockquote>
          </div>
          @endforeach
        </div>
        @if($testimonials->count() > 3)
        {{-- Pager ringkas (lihat catatan CSS "Navigasi testimoni"); diikat ke carousel oleh script.js --}}
        <div class="testimonial-pager" role="group" data-i18n-aria-label="testimonials.pagerLabel" aria-label="Navigasi testimoni">
          <button type="button" class="tp-btn tp-prev" data-i18n-aria-label="testimonials.prev" aria-label="Testimoni sebelumnya">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 5l-7 7 7 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="tp-status" aria-live="polite">
            <span class="tp-current">01</span>
            <span class="tp-track" aria-hidden="true"><span class="tp-bar" style="width: {{ round(100 / $testimonials->count(), 2) }}%"></span></span>
            <span class="tp-total">{{ sprintf('%02d', $testimonials->count()) }}</span>
          </div>
          <button type="button" class="tp-btn tp-next" data-i18n-aria-label="testimonials.next" aria-label="Testimoni berikutnya">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
        @endif
      </div>
    </section>
    @endif

    {{-- ABOUT / WHO WE ARE — foto full-bleed kiri, panel navy solid kanan.
         Judul/deskripsi/label/CTA tetap dari $setting->about_* (dwibahasa).
         Tombol mengarah ke halaman "Tentang Asa Production". --}}
    <section class="about-split" id="about">
      <div class="about-split_col about-split_col--photo wow fadeInLeft" data-wow-duration="0.9s">
        <img src="{{ $setting->about_image ? asset('storage/'.$setting->about_image) : asset('images/parallax.jpg') }}" alt="{{ $setting->site_title }}"/>
      </div>
      <div class="about-split_col about-split_col--text wow fadeInRight" data-wow-delay="0.15s" data-wow-duration="0.9s">
        <div class="about-split_inner">
          @if($setting->about_label)
          <span class="about-split_eyebrow"><span data-i18n-lang="id">{{ $setting->about_label }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->about_label_en ?: $setting->about_label }}</span></span>
          @else
          <span class="about-split_eyebrow" data-i18n="about.label">Who We Are</span>
          @endif
          <h2 class="about-split_title"><span data-i18n-lang="id">{!! nl2br(e($setting->about_title)) !!}</span><span data-i18n-lang="en" style="display:none">{!! nl2br(e($setting->about_title_en ?: $setting->about_title)) !!}</span></h2>
          @if($setting->about_description)
          <p class="about-split_desc"><span data-i18n-lang="id">{{ $setting->about_description }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->about_description_en ?: $setting->about_description }}</span></p>
          @endif
          <ul class="about-split_points">
            <li><span>01</span><span data-i18n="about.point1">Berdiri sejak 2019 di Sidoarjo, Jawa Timur</span></li>
            <li><span>02</span><span data-i18n="about.point2">Fokus pada pengembangan perangkat lunak dan website</span></li>
            <li><span>03</span><span data-i18n="about.point3">Solusi disesuaikan dengan alur kerja bisnis Anda</span></li>
          </ul>
          <div class="about-split_cta">
            <a href="{{ route('about') }}" class="btn">
              @if($setting->about_cta_text)
                <span data-i18n-lang="id">{{ $setting->about_cta_text }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->about_cta_text_en ?: $setting->about_cta_text }}</span>
              @else
                <span data-i18n="about.cta">Pelajari Lebih Lanjut</span>
              @endif
            </a>
          </div>
        </div>
      </div>
    </section>


    {{-- SERVICES FEATURES --}}
    @if($services->count())
    <section class="well well__ins1 center" id="services">
      <div class="container">
        <div class="wow fadeInUp" data-wow-duration="0.9s">
          <h2 class="section-heading" data-i18n="services.title">Keunggulan Kami</h2>
          <hr class="section-heading_bar"/>
          <p data-i18n="services.subtitle">Alasan klien memilih layanan kami</p>
        </div>
        <div class="row">
          @foreach($services as $service)
          <div class="grid_4 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->index % 3) }}s" data-wow-duration="0.8s">
            @if($service->icon && !str_contains($service->icon, '/'))
              <div class="{{ $service->icon }}"></div>
            @elseif($service->icon)
              <img src="{{ asset('storage/'.$service->icon) }}" alt="{{ $service->title }}" loading="lazy" style="max-width:120px;margin:0 auto;"/>
            @endif
            <hr/>
            <h3>@bi($service, 'title')</h3>
            <p>@bi($service, 'description', ['nl2br' => true])</p>
          </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    {{-- PORTFOLIO / GALLERY --}}
    @if($portfolios->count())
    <section class="thumb-container" id="gallery">
      <div class="container center wow fadeInUp" data-wow-duration="0.9s" style="width:100%;margin-bottom:24px;">
        <h2 class="section-heading" data-i18n="gallery.title">Portofolio Kami</h2>
        <hr class="section-heading_bar"/>
        <p data-i18n="gallery.subtitle">Beberapa proyek terbaik yang telah kami kerjakan</p>
      </div>
      @foreach($portfolios as $item)
      <div class="item wow zoomIn" data-wow-delay="{{ 0.08 * ($loop->index % 6) }}s" data-wow-duration="0.7s">
        {{-- data-full, data-title & data-desc dipakai portfolio-lightbox.js untuk
             overview gambar yang bisa discroll/digeser ke samping, sama seperti di
             halaman /portfolio. href tetap ada sebagai fallback jika JS mati. --}}
        <a class="portfolio-thumb" style="padding-bottom:73.17073170731707%;" href="{{ asset('storage/'.$item->image) }}" data-full="{{ asset('storage/'.$item->image) }}" data-title="{{ $item->title }}" data-desc="{{ $item->description }}">
          <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" loading="lazy"/>
          <span class="thumb_overlay"></span>
        </a>
      </div>
      @endforeach
    </section>
    @endif


    {{-- ARTIKEL TERBARU (BLOG) --}}
    @if($setting->blog_section_enabled && $latestPosts->count())
    <section class="well blog-section" id="blog">
      <div class="container center wow fadeInUp" data-wow-duration="0.9s">
        <h2 class="section-heading" data-i18n="blog.title">Artikel Terbaru</h2>
        <hr class="section-heading_bar"/>
        <p data-i18n="blog.subtitle">Tips dan wawasan seputar dunia produksi kreatif</p>
      </div>
      <div class="container">
        <div class="row blog-section_grid">
          @foreach($latestPosts as $post)
          <div class="grid_4 wow fadeInUp" data-wow-delay="{{ 0.1 * $loop->index }}s" data-wow-duration="0.8s">
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
              <div class="blog-card_thumb">
                @if($post->thumbnail)
                  <img src="{{ asset('storage/'.$post->thumbnail) }}" alt="{{ $post->title }}" loading="lazy"/>
                @endif
              </div>
              <div class="blog-card_body">
                <div class="blog-card_meta">
                  <span class="blog-card_date">{{ $post->author ?: 'Admin' }} &bull; {{ optional($post->published_at)->translatedFormat('d M Y, H:i') }}</span>
                  <span class="blog-card_likes" data-likes-for="{{ $post->slug }}">
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
                    {{ $post->likes }}
                  </span>
                </div>
                <h3 class="blog-card_title">@bi($post, 'title')</h3>
                <p class="blog-card_excerpt">@biexcerpt($post, 100)</p>
                <span class="blog-card_readmore" data-i18n="blog.readmore">Baca Selengkapnya →</span>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        <div class="center" style="margin-top:44px;">
          <a href="{{ route('blog.index') }}" class="btn btn-sm" data-i18n="blog.cta">Lihat Semua Artikel</a>
        </div>
      </div>
    </section>
    @endif

    {{-- FAQ --}}
    @if($faqs->count())
    <section class="well faq-section" id="faq">
      <div class="container center wow fadeInUp" data-wow-duration="0.9s">
        <h2 class="section-heading" data-i18n="faq.title">F.A.Q</h2>
        <hr class="section-heading_bar"/>
        <p data-i18n="faq.subtitle">Pertanyaan yang sering diajukan</p>
      </div>
      <div class="container">
        <div class="faq-list">
          @foreach($faqs as $faq)
          <details class="faq-item wow fadeInUp" data-wow-delay="{{ 0.08 * ($loop->index % 6) }}s" data-wow-duration="0.7s">
            <summary>
              <span>@bi($faq, 'question')</span>
              <i class="fa fa-angle-right" aria-hidden="true"></i>
            </summary>
            <div class="faq-answer">
              <p>@bi($faq, 'answer', ['nl2br' => true])</p>
            </div>
          </details>
          @endforeach
        </div>
      </div>
    </section>
    @endif
  </main>

  {{-- ======================== FOOTER ======================== --}}
  <footer id="contact">
    @php($footerBgUrl = $setting->footer_bg ? asset('storage/'.$setting->footer_bg) : asset('images/parallax2.jpg'))
    <div class="parallax" data-url="{{ $footerBgUrl }}" data-mobile="true" data-speed="0.5 " data-direction="inverted" style="background-image:url('{{ $footerBgUrl }}');background-size:cover;background-position:center center;">
      <div class="well4">
        <div class="container center wow fadeInUp" data-wow-delay="0.2s">
          <hr/>
          <h2><span data-i18n-lang="id">{{ $setting->footer_title }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->footer_title_en ?: $setting->footer_title }}</span></h2>
          <h3><span data-i18n-lang="id">{{ $setting->footer_subtitle }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->footer_subtitle_en ?: $setting->footer_subtitle }}</span></h3>
          <a class="btn" href="{{ $setting->footer_cta_link ?: 'https://wa.me/'.$setting->whatsapp_number }}"><span data-i18n-lang="id">{{ $setting->footer_cta_text }}</span><span data-i18n-lang="en" style="display:none">{{ $setting->footer_cta_text_en ?: $setting->footer_cta_text }}</span></a>
        </div>
      </div>
    </div>

    {{-- FOOTER COLUMNS --}}
    <div class="footer-columns">
      <div class="container">
        <div class="row wow fadeInUp" data-wow-duration="0.9s">
          <div class="grid_3 footer-col footer-col--brand">
            <a href="./" class="footer-brand">
              <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}" alt="{{ $setting->site_title }}"/>
              <span style="font-family:'League Spartan', sans-serif !important; text-transform:none !important;">{{ $setting->site_title }}</span>
            </a>
            @if($setting->footer_address)
            <p>{{ $setting->footer_address }}</p>
            @endif
            <p><strong data-i18n="footer.phoneLabel">Phone:</strong> {{ $setting->whatsapp_number }}</p>
            @if($setting->footer_email)
            <p><strong data-i18n="footer.emailLabel">Email:</strong> {{ $setting->footer_email }}</p>
            @endif
          </div>

          <div class="grid_2 footer-col footer-col--nav">
            <h4 data-i18n="footer.navTitle">Navigasi</h4>
            <ul class="footer-links">
              <li><a href="{{ route('blog.index') }}" data-i18n="nav.blog">Blog</a></li>
              <li><a href="{{ route('portfolio.index') }}" data-i18n="nav.portfolio">Portofolio</a></li>
              <li><a href="{{ route('about') }}" data-i18n="nav.about">Tentang Kami</a></li>
              <li><a href="{{ route('contact') }}" data-i18n="nav.contact">Kontak</a></li>
            </ul>
          </div>

          <div class="grid_2 footer-col footer-col--services">
            <h4 data-i18n="footer.servicesTitle">Layanan</h4>
            <ul class="footer-links">
              <li><a href="{{ route('solusi.website') }}" data-i18n="nav.servicesWebsite">Jasa Pembuatan Website</a></li>
              <li><a href="{{ route('solusi.app-mobile') }}" data-i18n="nav.servicesAppMobile">Jasa Pembuatan App Mobile</a></li>
              <li><a href="{{ route('solusi.custom-system') }}" data-i18n="nav.servicesCustomSystem">Jasa Pembuatan Sistem Kustom</a></li>
            </ul>
          </div>

          <div class="grid_2 footer-col footer-col--company">
            <h4 data-i18n="footer.companyTitle">Perusahaan</h4>
            <ul class="footer-links">
              <li><a href="#about" data-i18n="footer.aboutUs">Tentang Kami</a></li>
              <li><a href="#services" data-i18n="footer.ourServices">Layanan Kami</a></li>
              <li><a href="{{ route('contact') }}" data-i18n="footer.contactUs">Hubungi Kami</a></li>
              <li><a href="{{ route('legal.terms') }}" data-i18n="nav.terms">Ketentuan Layanan</a></li>
              <li><a href="{{ route('legal.privacy') }}" data-i18n="nav.privacy">Kebijakan Privasi</a></li>
            </ul>
          </div>

          <div class="grid_3 footer-col">
            @if($certificates->count())
            <h4 data-i18n="footer.certTitle">Sertifikat</h4>
            <div class="footer-badges">
              @foreach($certificates as $cert)
              <img src="{{ asset('storage/'.$cert->image) }}" alt="{{ $cert->name ?? 'Sertifikat' }}" loading="lazy"/>
              @endforeach
            </div>
            @endif
            <h4 style="margin-top:24px;" data-i18n="footer.findUsTitle">Temukan Kami</h4>
            <div class="footer-social">
              @if($setting->social_instagram)<a href="{{ $setting->social_instagram }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>@endif
              @if($setting->social_facebook)<a href="{{ $setting->social_facebook }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>@endif
              @if($setting->social_tiktok)<a href="{{ $setting->social_tiktok }}" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" style="vertical-align:middle;"><path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6c0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64c0 3.33 2.76 5.7 5.69 5.7c3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z"/></svg></a>@endif
              @if($setting->social_linkedin)<a href="{{ $setting->social_linkedin }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>@endif
              @if($setting->social_youtube)<a href="{{ $setting->social_youtube }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>@endif
              @if($setting->social_x)<a href="{{ $setting->social_x }}" target="_blank" rel="noopener" aria-label="X"><i class="fa fa-twitter" aria-hidden="true"></i></a>@endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="well5 center">
      <div class="container">
        <div class="copyright">
          © <span id="copyright-year"></span> {{ $setting->site_title }}. <span data-i18n="footer.rights">All Rights Reserved</span>
        </div>
      </div>
    </div>
  </footer>
</div>

{{-- ======================== PORTFOLIO LIGHTBOX ======================== --}}
{{-- Overview gambar portofolio: bisa discroll/digeser ke samping (swipe di
     HP, drag mouse atau tombol panah di desktop) untuk pindah antar gambar,
     sama seperti di halaman /portfolio. --}}
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

@include('partials.chatbot-widget')

<script src="{{ asset('js/script.js') }}"></script>
{{-- script.js otomatis meng-include jquery.fancybox, owl-carousel, wow.js,
     jquery.vide.js, dan library lain lewat document.write(), sesuai
     struktur asli template. Jangan load ulang manual di sini. --}}
<script src="{{ asset('js/portfolio-lightbox.js') }}"></script>
<script>
  (function () {
    // ===== Language switcher (ID / EN) =====
    // Dictionary + apply/persist logic now live in the shared
    // library (js/i18n-lib.js + js/i18n-data.js, loaded in <head>)
    // so home, about, contact and blog pages all share one
    // implementation instead of each keeping its own copy.
    if (window.I18n && window.SITE_I18N) {
      window.I18n.init(window.SITE_I18N);
      window.I18n.bindLangSwitcher();
    }

    window.__siteLang = function () { return window.I18n ? window.I18n.getLang() : 'id'; };
    window.__t = function (key) { return window.I18n ? window.I18n.t(key) : key; };

    var nav = document.getElementById('site-nav');
    var toggle = document.getElementById('navToggle');
    var menu = document.getElementById('navMenu');

    if (toggle && menu) {
      toggle.addEventListener('click', function () {
        menu.classList.toggle('is-open');
        toggle.classList.toggle('is-open');
      });
      menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          menu.classList.remove('is-open');
          toggle.classList.remove('is-open');
        });
      });
    }

    document.querySelectorAll('.dropdown-toggle').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var parent = btn.closest('.has-dropdown');
        var wasOpen = parent.classList.contains('is-open');
        document.querySelectorAll('.has-dropdown.is-open').forEach(function (li) {
          li.classList.remove('is-open');
        });
        if (!wasOpen) { parent.classList.add('is-open'); }
      });
    });
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.has-dropdown')) {
        document.querySelectorAll('.has-dropdown.is-open').forEach(function (li) {
          li.classList.remove('is-open');
        });
      }
    });

    // ===== Pindahkan tombol ganti bahasa ke dalam dropdown hamburger =====
    // Di layar mobile (<=991px) #langSwitcher dipindah jadi item terakhir
    // #navMenu (lewat slot kosong #navLangSlot) supaya jadi bagian dari
    // dropdown hamburger. Di layar desktop, elemen yang sama dikembalikan
    // ke posisi semula di .site-nav_utility — jadi tampilan desktop tidak
    // berubah sama sekali.
    (function () {
      var MOBILE_QUERY = '(max-width: 991px)';

      function placeLangSwitcher() {
        var lang = document.getElementById('langSwitcher');
        var slot = document.getElementById('navLangSlot');
        var utility = document.querySelector('.site-nav_utility');
        if (!lang || !slot || !utility) { return; }

        var isMobile = window.matchMedia(MOBILE_QUERY).matches;
        if (isMobile) {
          if (lang.parentElement !== slot) { slot.appendChild(lang); }
        } else if (lang.parentElement !== utility) {
          var toggleBtn = document.getElementById('navToggle');
          utility.insertBefore(lang, toggleBtn || null);
        }
      }

      placeLangSwitcher();

      var mq = window.matchMedia(MOBILE_QUERY);
      if (mq.addEventListener) {
        mq.addEventListener('change', placeLangSwitcher);
      } else if (mq.addListener) { // Safari lama
        mq.addListener(placeLangSwitcher);
      }
      window.addEventListener('resize', placeLangSwitcher);
    })();

    // ===== Liquid glass navbar: di hero navbar polos (transparan, flush
    // di atas); begitu discroll melewati SCROLL_THRESHOLD, class
    // 'is-scrolled' memicu morph ke kapsul liquid glass — transisinya
    // ditangani CSS (lihat blok <style> di atas), di sini cukup toggle
    // class-nya. Navbar SELALU tetap terlihat (tidak pernah disembunyikan
    // lewat translateY) baik scroll ke bawah maupun ke atas — sebelumnya
    // ada logic auto-hide yang membuat navbar hilang total dari layar
    // saat scroll ke bawah. =====
    if (nav) {
      var SCROLL_THRESHOLD = 24;    // px — batas mulai berubah dari polos ke liquid glass
      var ticking = false;

      function applyNavLiquidState() {
        var y = window.scrollY;
        nav.classList.toggle('is-scrolled', y >= SCROLL_THRESHOLD);
        nav.classList.remove('nav-liquid--hidden');
        ticking = false;
      }

      function requestNavUpdate() {
        if (!ticking) {
          window.requestAnimationFrame(applyNavLiquidState);
          ticking = true;
        }
      }

      window.addEventListener('scroll', requestNavUpdate, { passive: true });
      window.addEventListener('resize', requestNavUpdate);
      applyNavLiquidState();
    }

    var year = document.getElementById('copyright-year');
    if (year) { year.textContent = new Date().getFullYear(); }

    // Fallback: some browsers/embedded previews ignore the autoplay
    // attribute (or block it entirely inside an iframe without an
    // "allow=autoplay" permission), so we nudge playback multiple ways.
    var heroVideo = document.querySelector('.hero-bg-video');
    if (heroVideo) {
      heroVideo.muted = true; // some browsers only honor the JS property, not the attribute
      heroVideo.playsInline = true;

      var tryPlay = function () {
        var p = heroVideo.play();
        if (p && typeof p.catch === 'function') { p.catch(function () {}); }
      };

      ['loadedmetadata', 'loadeddata', 'canplay'].forEach(function (evt) {
        heroVideo.addEventListener(evt, tryPlay);
      });
      tryPlay();

      // Last-resort fallback: if autoplay was blocked by the browser,
      // the very first tap/scroll/click anywhere on the page resumes it.
      var resumeOnInteraction = function () {
        if (heroVideo.paused) { tryPlay(); }
        ['click', 'touchstart', 'scroll', 'keydown'].forEach(function (evt) {
          document.removeEventListener(evt, resumeOnInteraction);
        });
      };
      ['click', 'touchstart', 'scroll', 'keydown'].forEach(function (evt) {
        document.addEventListener(evt, resumeOnInteraction, { once: true, passive: true });
      });
    }
  })();
</script>
</body>
</html>
