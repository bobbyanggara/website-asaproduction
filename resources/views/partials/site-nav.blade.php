{{-- Navbar — struktur & markup disamakan persis dengan navbar landing page
     (resources/views/home.blade.php): logo, promo marquee, dropdown menu
     "Layanan" & "Info", language switcher, tombol WhatsApp, dan toggle mobile.
     Link jangkar (#about, #services, dst) diarahkan kembali ke route('home')
     karena halaman ini bukan halaman utama.

     Style di bawah ini SENGAJA ditanam langsung di partial (bukan
     mengandalkan public/css/site-nav-shared.css) supaya navbar liquid
     glass ini tampil identik & konsisten di semua halaman yang memakai
     partial ini, terlepas dari aturan apa pun yang ada di file CSS lain.
     Logic toggle/dropdown/lang-switch tetap dipegang public/js/site-nav.js
     seperti sebelumnya — script kecil di bawah cuma menambah efek baru
     (progres kaca + auto hide/reveal saat scroll) tanpa mengubah logic lama. --}}
<style>
  .site-nav{
    position: fixed !important;
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
  .site-nav.nav-liquid--hidden{ transform: translateX(-50%) translateY(-140%); }
  @media (prefers-reduced-motion: reduce){ .site-nav{ transition: none; } }
  {{-- Pantulan cahaya di kaca — dua lapis: ::before jadi "rim light" tipis &
       terang di tepi atas kapsul (khas iOS, seolah cahaya menimpa tepi kaca),
       ::after jadi sapuan cahaya diagonal (specular sheen) yang melintasi
       badan kapsul supaya terasa benar-benar seperti permukaan kaca
       melengkung, bukan cuma panel blur polos. --}}
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
  .site-nav.is-scrolled::before{ opacity: 1; }
  .site-nav.is-scrolled::after{ opacity: 1; }
  .site-nav_inner{
    position: relative;
    display: flex;
    align-items: center;
    gap: 18px;
    width: 100%;
    padding: 10px 14px 10px 18px;
    transition: padding .4s cubic-bezier(.22,1,.36,1);
  }
  .site-nav.is-scrolled .site-nav_inner{ padding-top: 7px; padding-bottom: 7px; }
  .site-nav_brand{
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 0 0 auto;
    text-decoration: none;
    transition: transform .2s cubic-bezier(.22,1,.36,1);
  }
  .site-nav_brand:active{ transform: scale(.96); }
  .site-nav_brand img{ height: 32px; width: auto; display: block; transition: height .4s cubic-bezier(.22,1,.36,1); }
  .site-nav.is-scrolled .site-nav_brand img{ height: 28px; }
  .site-nav_brand span{ font-family: 'League Spartan', sans-serif; color: #fff; font-size: 16px; line-height: 1; font-weight: 800; letter-spacing: .01em; white-space: nowrap; transition: color .2s ease; }
  .site-nav_brand:hover span{ color: var(--brand-accent, #2E8BFF); }
  {{-- Menu sekarang flex:1 1 auto (bukan 0 0 auto) + justify-content:center
       Diubah (permintaan user): menu sekarang flex:0 0 auto supaya nempel
       rapat di sebelah kiri grup kanan (bahasa + tombol WhatsApp), bukan
       ikut menggelembung mengisi sisa ruang. Sisa ruang diserap penuh oleh
       .site-nav_marquee di sebelahnya, jadi teks berjalan otomatis lebih
       lebar. --}}
  .site-nav_menu{ display: flex; align-items: center; justify-content: flex-start; gap: 22px; list-style: none; margin: 0; padding: 0; flex: 0 0 auto; min-width: 0; }
  {{-- Setiap <li> juga dijadikan flex container sendiri (bukan cuma isinya)
       supaya link <a> biasa (Home) dan tombol dropdown (<button>) sama-sama
       dipusatkan oleh <li>-nya, bukan cuma oleh dirinya sendiri — ini yang
       sebelumnya bikin teks "Home"/"Beranda" terlihat turun sedikit
       dibanding "Layanan"/"Info", karena <a> & <button> punya model kotak
       bawaan browser yang berbeda. --}}
  .site-nav_menu > li{ display: flex; align-items: center; }
  {{-- Item menu biasa (<a>) dan tombol dropdown (<button>) disamakan
       persis: inline-flex + align-items:center + line-height:1 supaya
       baseline teks keduanya sejajar, terlepas dari salah satunya punya
       ikon chevron atau tidak. --}}
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
  {{-- Item WhatsApp versi mobile (di dalam dropdown hamburger): tersembunyi
       secara default, cuma dimunculkan di breakpoint mobile di bawah. --}}
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
  .site-nav_marquee-track{ display: inline-block; white-space: nowrap; padding-left: 100%; animation: site-nav-marquee 14s linear infinite; }
  .site-nav_marquee-track span{ display: inline-block; padding: 0 40px; font-size: 13px; font-weight: 600; color: #ffd166; }
  @keyframes site-nav-marquee{ 0%{ transform: translateX(0); } 100%{ transform: translateX(-100%); } }
  .site-nav_menu .has-dropdown{ position: relative; }
  .dropdown-toggle{
    background: none;
    border: none;
    color: rgba(255,255,255,.92);
    font-family: inherit;
    font-size: 14px;
    line-height: 1;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    padding: 0;
  }
  .dropdown-toggle:hover{ color: var(--brand-accent, #2E8BFF); }
  .dropdown-toggle:active{ opacity: .6; }
  .dropdown-toggle i{ font-size: 11px; line-height: 1; transition: transform .2s ease; }
  .has-dropdown:hover .dropdown-toggle i, .has-dropdown.is-open .dropdown-toggle i{ transform: rotate(180deg); }
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
  .dropdown-menu li + li{ margin-top: 2px; }
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

  .dropdown-menu a::after{ display: none; }
  .dropdown-menu a:hover{ color: #fff; background: rgba(255,255,255,.1); }
  .dropdown-menu a:active{ background: rgba(255,255,255,.18); }
  .has-dropdown:hover .dropdown-menu, .has-dropdown.is-open .dropdown-menu{ opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0) scale(1); }
  {{-- Dropdown "Layanan" — tiap item punya ikon bulat + judul + subtitle
       (2 baris), jadi butuh lebar & layout flex sendiri (a::flex, bukan
       block seperti dropdown "Info" yang tetap 1 baris polos). --}}
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
  .site-nav_utility{ display: flex; align-items: center; gap: 14px; flex: 0 0 auto; }
  .site-nav_lang{ position: relative; }
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
  .lang-toggle:hover{ background: rgba(255,255,255,.18); }
  .lang-toggle:active{ transform: scale(.93); }
  .lang-toggle i{ font-size: 9px; opacity: .8; }
  .lang-flag-icon{ display: block; width: 22px; height: 15px; border-radius: 3px; overflow: hidden; box-shadow: 0 0 0 1px rgba(255,255,255,.3); flex-shrink: 0; }
  .lang-flag-icon svg{ display: block; width: 100%; height: 100%; }
  #langSwitcher .dropdown-menu.lang-menu{ left: auto; right: 0; min-width: 140px; padding: 8px; transform: translateY(6px) scale(.96); }
  #langSwitcher.is-open .dropdown-menu.lang-menu, #langSwitcher:hover .dropdown-menu.lang-menu{ transform: translateY(0) scale(1); }
  .lang-menu li + li{ margin-top: 4px !important; }
  .lang-option{ display: flex; align-items: center; gap: 8px; width: 100%; height: 32px; background: none; border: 2px solid transparent; border-radius: 6px; padding: 0 10px 0 6px; cursor: pointer; }
  .lang-option:hover{ background: rgba(255,255,255,.08); }
  .lang-option.is-active{ border-color: var(--brand-accent, #2E8BFF); background: rgba(255,255,255,.08); }
  .lang-option-label{ color: #fff; font-size: 12px; line-height: 1; white-space: nowrap; }
  #navLangSlot:empty{ display: none; }
  @media (max-width: 991px){
    .site-nav_toggle{ display: block; }
    .site-nav_marquee{ display: none; }
    {{-- Tombol WhatsApp dipindah ke dalam dropdown hamburger di mobile:
         sembunyikan versi navbar-nya, tampilkan versi di dalam menu. --}}
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
      {{-- Liquid glass: blur kuat + latar semi-transparan gelap (bukan
           solid) supaya efek kaca terasa, tapi alpha tetap cukup tinggi
           (.62) supaya kontras teks putih di atasnya tetap terjaga terlepas
           dari seberapa terang konten di belakangnya. --}}
      background: linear-gradient(180deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,.02) 40%, rgba(255,255,255,.04) 100%), rgba(9, 18, 36, .62);
      -webkit-backdrop-filter: blur(38px) saturate(220%) brightness(1.12) contrast(1.05);
      backdrop-filter: blur(38px) saturate(220%) brightness(1.12) contrast(1.05);
      border: 1px solid rgba(255,255,255,.28);
      box-shadow: 0 20px 46px -12px rgba(0,0,0,.55), inset 0 1.5px 0 rgba(255,255,255,.3), inset 0 -1px 0 rgba(0,0,0,.2), inset 0 0 28px rgba(255,255,255,.06);
      transition: max-height .35s cubic-bezier(.22,1,.36,1), opacity .25s ease;
    }
    .site-nav_menu.is-open{ max-height: min(70vh, 520px); opacity: 1; }
    {{-- PENTING: aturan dasar (di luar media query) mengeset
         `.site-nav_menu > li{ display:flex }` tanpa flex-direction, jadi
         default-nya row (horizontal). Sebelumnya, di mobile cuma
         align-items yang di-override — tombol dropdown ("Solusi"/"Info")
         dan daftar submenu-nya jadi nempel BERSEBELAHAN (bukan bertumpuk
         ke bawah), sehingga teks submenu kepotong di tepi kanan panel.
         flex-direction:column di sini yang jadi kuncinya. --}}
    .site-nav_menu > li{ display: flex; flex-direction: column; align-items: stretch; }
    {{-- Garis pemisah tipis antar-item level atas (Produk / Solusi / Info /
         WhatsApp / bahasa) supaya baris-baris menu nggak menempel satu sama
         lain. Diganti dari nth-child(2)/(3) yang statis (rawan meleset kalau
         menu "Produk" tidak ada karena belum ada produk aktif) ke selector
         sibling (`li + li`) yang otomatis mengikuti jumlah item apa pun.
         Item yang sudah punya garis sendiri (tombol WhatsApp) dikecualikan
         supaya tidak dobel. --}}
    .site-nav_menu > li + li:not(.site-nav_cta-item):not(#navLangSlot){ border-top: 1px solid rgba(255,255,255,.08); }
    .site-nav_menu > li > a{
      display: block;
      padding: 14px 16px;
      border-radius: 14px;
      font-size: 14.5px;
      transition: background .18s ease, color .18s ease;
    }
    .site-nav_menu > li > a:active{ background: rgba(255,255,255,.08); }
    .site-nav_menu .has-dropdown{ width: 100%; }
    .dropdown-toggle{
      width: 100%;
      padding: 14px 16px;
      border-radius: 14px;
      font-size: 14.5px;
      justify-content: space-between;
      transition: background .18s ease;
    }
    .has-dropdown.is-open .dropdown-toggle{ background: rgba(255,255,255,.06); border-radius: 14px 14px 0 0; }
    .dropdown-toggle:active{ background: rgba(255,255,255,.08); }
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
    {{-- Panel kecil di dalam panel besar: latar gelap semi-transparan (lebih
         gelap dari panel induk) supaya sub-item (mis. daftar layanan) tetap
         terlihat jelas sebagai "laci" tersendiri walau kedua panel sama-sama
         kaca/blur — bukan latar solid, jadi nuansa liquid glass tetap kerasa. --}}
    .has-dropdown.is-open .dropdown-menu{
      max-height: 340px;
      overflow-y: auto;
      transform: none;
      background: rgba(0,0,0,.3);
      border-radius: 0 0 14px 14px;
      margin: 0 0 8px;
      padding: 6px;
    }
    .dropdown-menu a{ padding: 11px 14px 11px 16px; font-size: 13.5px; border-radius: 10px; }
    {{-- Reset min-width:300px dari versi desktop supaya submenu tidak
         berpotensi lebih lebar dari layar di HP yang sangat sempit. --}}
    .dropdown-menu--services{ min-width: 0; }
    {{-- Versi mobile dropdown "Layanan": ikon lebih kecil, subtitle tetap
         ikut turun ke baris baru, indent kiri disamakan dengan item lain. --}}
    .dropdown-menu--services a{ padding: 10px 14px 10px 16px; gap: 10px; }
    .dropdown-menu--services .dropdown-menu_icon{ width: 30px; height: 30px; font-size: 13px; border-radius: 8px; }
    .dropdown-menu--services .dropdown-menu_title{ font-size: 13.5px; }
    .dropdown-menu--services .dropdown-menu_subtitle{ font-size: 11.5px; }
    .site-nav_utility{ gap: 10px; margin-left: auto; }
    .lang-toggle{ padding: 5px 8px; }
    .lang-flag-icon{ width: 20px; height: 14px; }
    #navLangSlot{ border-top: 1px solid rgba(255,255,255,.1); margin-top: 6px; padding-top: 8px; }
    #navLangSlot .lang-toggle{ width: 100%; background: none; border: none; border-radius: 14px; padding: 12px 16px; justify-content: space-between; }
    #navLangSlot .lang-toggle:hover{ background: rgba(255,255,255,.08); }
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
    #langSwitcher.is-open .dropdown-menu.lang-menu{ max-height: 200px; }
    #navLangSlot .lang-option{ width: 100%; height: auto; border-radius: 0; padding: 12px 40px; }
  }
</style>
<nav class="site-nav blog-nav" id="site-nav">
  <div class="container site-nav_inner">
    <a href="{{ route('home') }}" class="site-nav_brand">
      <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : asset('images/logo.png') }}" alt="{{ $setting->site_title ?? 'Asa Production' }}"/>
      <span>{{ $setting->site_title ?? 'Asa Production' }}</span>
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
      {{-- Slot kosong: JS (site-nav.js) memindahkan #langSwitcher ke sini
           saat tampilan mobile, supaya ganti bahasa jadi bagian dari
           dropdown hamburger. Kosong = tersembunyi otomatis (di desktop). --}}
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
      {{-- Language switcher: dropdown, tampilan bendera SVG — sama seperti landing page --}}
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
              <span class="lang-flag-icon" aria-hidden="true">
                <svg viewBox="0 0 3 2" xmlns="http://www.w3.org/2000/svg"><rect width="3" height="1" fill="#CE1126"/><rect y="1" width="3" height="1" fill="#FFFFFF"/></svg>
              </span>
              <span class="lang-option-label" aria-hidden="true">Indonesia</span>
            </button>
          </li>
          <li role="presentation">
            <button type="button" class="lang-option" data-lang="en" role="option" aria-checked="false" aria-label="English">
              <span class="lang-flag-icon" aria-hidden="true">
                <svg viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                  <rect width="60" height="30" fill="#00247d"/>
                  <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                  <path d="M0,0 L60,30 M60,0 L0,30" stroke="#cf142b" stroke-width="2"/>
                  <path d="M30,0 V30 M0,15 H60" stroke="#fff" stroke-width="10"/>
                  <path d="M30,0 V30 M0,15 H60" stroke="#cf142b" stroke-width="6"/>
                </svg>
              </span>
              <span class="lang-option-label" aria-hidden="true">English</span>
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
<script>
  // Efek liquid glass tambahan (progres blur halus + auto hide/reveal saat
  // scroll). Sengaja dipisah dari site-nav.js supaya tidak mengganggu logic
  // toggle/dropdown/ganti-bahasa yang sudah ditangani file itu.
  (function () {
    var nav = document.getElementById('site-nav');
    var menu = document.getElementById('navMenu');
    if (!nav) return;

    var SCROLL_THRESHOLD = 24;
    var ticking = false;

    // Navbar sekarang SELALU tetap terlihat (tidak pernah disembunyikan
    // lewat translateY), baik scroll ke bawah maupun ke atas — sebelumnya
    // ada logic auto-hide (menambahkan class 'nav-liquid--hidden' saat
    // scroll ke bawah) yang membuat navbar hilang total dari layar. Yang
    // dipertahankan hanya morph ke kapsul liquid glass ('is-scrolled')
    // begitu melewati SCROLL_THRESHOLD.
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
  })();
</script>
