{{--
  Style bersama untuk semua halaman /solusi/* (App Mobile, Website, Sistem
  Kustom). Ditaruh di satu partial (pola sama dengan partials/breadcrumb.blade.php)
  supaya tampilan Hero / Preview Solusi / Keunggulan / Portofolio konsisten
  di ketiga halaman dan cukup diubah di satu tempat.
--}}
<style>
  {{--
    Eyebrow label — letter-spacing disamakan persis dengan
    .about-section_eyebrow / .blog-masthead .eyebrow (2px flat, bukan .12em
    relatif) supaya rasanya satu komponen yang sama di semua halaman.
    Warnanya tetap var(--brand-light) (bukan --brand-accent seperti eyebrow
    di atas masthead gelap) karena badge ini duduk di atas background terang,
    dan brand-light kontrasnya lebih pas di situ.
  --}}
  .solusi-eyebrow{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--brand-light);
  }

  {{--
    Hero load-in animation — disamakan persis dengan home.blade.php
    (.hero-fade-in_item / @keyframes hero-fade-in-up) supaya hero di
    ketiga halaman /solusi/* punya "kesan hidup" yang sama seperti hero
    halaman utama, bukan cuma muncul statis begitu halaman selesai load.
    nth-child di-extend sampai (4) karena .solusi-hero_text di sini
    punya 4 anak langsung (eyebrow, h1, p, tombol CTA) vs 3 di home.
  --}}
  .hero-fade-in_item{
    opacity: 0;
    transform: translateY(22px);
    animation: hero-fade-in-up .9s cubic-bezier(.22,1,.36,1) forwards;
  }
  .hero-fade-in_item:nth-child(1){ animation-delay: .1s; }
  .hero-fade-in_item:nth-child(2){ animation-delay: .25s; }
  .hero-fade-in_item:nth-child(3){ animation-delay: .4s; }
  .hero-fade-in_item:nth-child(4){ animation-delay: .55s; }
  @keyframes hero-fade-in-up{
    to{ opacity: 1; transform: translateY(0); }
  }
  @media (prefers-reduced-motion: reduce){
    .hero-fade-in_item{ animation: none; opacity: 1; transform: none; }
  }

  /* ===== HERO ===== */
  .solusi-hero{
    padding: 56px 0 8px;
    background: linear-gradient(180deg, #f4f8ff 0%, #ffffff 100%);
  }
  .solusi-hero_inner{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 48px;
    flex-wrap: wrap;
  }
  .solusi-hero_text{
    flex: 1 1 420px;
    max-width: 620px;
  }
  {{--
    h1 headline — sebelumnya cuma set warna/ukuran, jadi ikut warisan aturan
    global "h1,h2,h3,h4,h5,h6{text-transform:uppercase}" di style.css dan
    tampil KAPITAL SEMUA sepanjang satu kalimat penuh. Halaman lain yang
    headline-nya juga kalimat panjang (about-masthead h1, blog-masthead h1)
    sudah eksplisit menonaktifkan itu (text-transform:none) — di sini
    disamakan. Font-family League Spartan dipasang juga supaya benar-benar
    dipakai (sebelumnya di-load di <head> tapi tidak pernah dipanggil di
    CSS manapun di file ini) dan match dengan gaya headline utama di
    home.blade.php (header.vide .hero-text h2 & .brand_name), bukan cuma
    fallback ke Ubuntu biasa seperti body text.
  --}}
  .solusi-hero_text h1{
    margin: 14px 0 18px;
    font-family: 'League Spartan', sans-serif;
    font-size: clamp(28px, 4vw, 42px);
    line-height: 1.22;
    font-weight: 700;
    text-transform: none;
    letter-spacing: normal;
    color: var(--brand-dark);
  }
  .solusi-hero_text p{
    margin: 0 0 28px;
    font-size: 16.5px;
    line-height: 1.7;
    color: #5b6472;
  }
  .solusi-hero_cta{
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .solusi-hero_media{
    flex: 1 1 320px;
    max-width: 480px;
  }
  {{--
    Foto hero — disamakan dengan pola "foto di samping teks" yang sudah ada
    di about.blade.php (.about-hero-grid .about-photo): rounded 18px +
    drop shadow, bukan foto polos tanpa bingkai seperti sebelumnya.
  --}}
  .solusi-hero_media img{
    width: 100%;
    height: auto;
    display: block;
    border-radius: 18px;
    box-shadow: 0 20px 45px -22px rgba(0,34,76,.35);
  }

  /* ===== PREVIEW SOLUSI ===== */
  .solusi-preview_media{
    max-width: 900px;
    margin: 36px auto 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 24px 54px -20px rgba(0,34,76,.28);
  }
  .solusi-preview_media img,
  .solusi-preview_media video{
    width: 100%;
    height: auto;
    display: block;
  }
  .solusi-preview_caption{
    max-width: 700px;
    margin: 18px auto 0;
    color: #5b6472;
    font-size: 15px;
  }

  {{--
    ===== KEUNGGULAN =====
    Kartu ini disamakan SATU BANDING SATU dengan "Services Feature Cards"
    (.well__ins1 .grid_4) di home.blade.php / style.css: radius, padding,
    box-shadow default, warna border, jarak translateY saat hover, dan
    kekuatan shadow saat hover semuanya memakai angka yang sama persis
    seperti di beranda (sebelumnya beda-beda tipis — radius 14px vs 16px,
    tanpa shadow default, translateY 6px vs 8px — makanya kartunya kelihatan
    "mirip tapi enggak sama" dibanding beranda). Sapuan gradient brand saat
    hover + ikon jadi putih (invert) & transition timing (.35s ease) juga
    disamakan persis dengan punya home.

    Catatan tinggi & lebar kartu: .row/.grid_4 di sini masih grid lama
    berbasis float (lihat grid.css) dengan width FIXED per breakpoint
    (mis. 370px/300px/236px) — jadi height:100% pada .solusi-benefit-card
    saja tidak cukup untuk menyamakan tinggi antar kartu kalau panjang
    teksnya beda, dan tidak otomatis membagi rata lebar container juga.
    .solusi-benefits .row di-override jadi grid khusus di section ini saja
    (tidak menyentuh .row global) supaya kartu sejajar/simetris seperti
    section Layanan di halaman utama.

    Kenapa CSS Grid, bukan flexbox+flex-grow (versi sebelumnya): section
    Layanan di beranda SELALU berisi tepat 3 kartu, jadi 3 kolom fixed
    width-nya otomatis rapi. Section Keunggulan di sini jumlah kartunya
    ikut data admin (halaman /solusi/* kebetulan 3, tapi beberapa /produk/*
    ada 4). Dengan flex-grow: 1, begitu jumlah kartu TIDAK kelipatan 3,
    kartu sisa di baris terakhir (mis. kartu ke-4 sendirian) ikut melar
    flex-grow mengisi SISA lebar & tinggi barisnya sendiri — jadi kelihatan
    lebih besar/tidak sejajar dengan kartu di baris atasnya (ini penyebab
    kartu Keunggulan di /solusi & /produk kelihatan tidak simetris
    dibanding beranda). CSS Grid dengan kolom fixed (repeat(3,1fr) dst.)
    tidak punya masalah ini: setiap kolom selalu sama lebar di SEMUA baris,
    dan kartu yang sendirian di baris terakhir tetap seukuran kartu
    lainnya (sisa slot di baris itu dibiarkan kosong, tidak ikut melar).
    align-items:stretch bawaan grid juga otomatis menyamakan tinggi kartu
    per baris, jadi height:100% di .solusi-benefit-card tetap perlu
    dipertahankan. Breakpoint kolomnya disamakan dengan pola
    .solusi-portfolio_grid di bawah (3 → 2 → 1 kolom) supaya konsisten.
  --}}
  .solusi-benefits .row{
    margin-top: 32px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    align-items: stretch;
    margin-left: 0;
    gap: 30px;
  }
  .solusi-benefits .row > [class*="grid_"]{
    display: flex;
    float: none;
    width: auto;
    margin-left: 0;
  }
  @media (max-width: 767px){
    .solusi-benefits .row{ grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 520px){
    .solusi-benefits .row{ grid-template-columns: 1fr; }
  }
  .solusi-benefit-card{
    box-sizing: border-box;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #fff;
    border: 1px solid rgba(0,34,76,.06);
    border-radius: 16px;
    padding: 48px 30px 40px;
    box-shadow: 0 6px 24px rgba(0,34,76,.08);
    text-align: center;
    cursor: pointer;
    transition: background .35s ease, box-shadow .35s ease, transform .35s ease, border-color .35s ease;
  }
  .solusi-benefit-card:hover,
  .solusi-benefit-card:focus-within{
    transform: translateY(-8px);
    background: linear-gradient(135deg, var(--brand-accent), var(--brand-light) 55%, var(--brand-dark));
    border-color: transparent;
    box-shadow: 0 16px 40px rgba(0,34,76,.28);
  }
  @media (max-width: 767px){
    .solusi-benefit-card{
      padding: 36px 24px 30px;
    }
  }
  .solusi-benefit-card_icon{
    width: 96px;
    height: 96px;
    margin: 0 auto 24px;
    border-radius: 50%;
    background: #eaf2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .35s ease;
  }
  .solusi-benefit-card:hover .solusi-benefit-card_icon,
  .solusi-benefit-card:focus-within .solusi-benefit-card_icon{
    background: rgba(255,255,255,.16);
  }
  .solusi-benefit-card_icon img{
    width: 52px;
    height: 52px;
    object-fit: contain;
    transition: transform .35s ease, filter .35s ease;
  }
  .solusi-benefit-card:hover .solusi-benefit-card_icon img,
  .solusi-benefit-card:focus-within .solusi-benefit-card_icon img{
    transform: scale(1.08);
    filter: brightness(0) invert(1) drop-shadow(0 2px 6px rgba(0,0,0,.15));
  }
  .solusi-benefit-card_icon i{
    font-size: 42px;
    color: var(--brand-light);
    transition: color .35s ease;
  }
  .solusi-benefit-card:hover .solusi-benefit-card_icon i,
  .solusi-benefit-card:focus-within .solusi-benefit-card_icon i{
    color: #fff;
  }
  .solusi-benefit-card h3{
    margin: 0 0 12px;
    font-size: 19px;
    font-weight: 700;
    color: var(--brand-dark);
    transition: color .35s ease;
  }
  .solusi-benefit-card p{
    margin: 0;
    font-size: 14.5px;
    line-height: 1.65;
    color: #5b6472;
    transition: color .35s ease;
  }
  .solusi-benefit-card:hover h3,
  .solusi-benefit-card:hover p,
  .solusi-benefit-card:focus-within h3,
  .solusi-benefit-card:focus-within p{
    color: #fff;
  }

  {{--
    Jarak judul section ke garis bawahnya (h2 + hr). Global style.css memberi
    h2 line-height 50px + `* + hr{margin-top:28px}`, jadi ada ~40px+ ruang
    kosong antara teks dan garis. Diperkecil khusus section /solusi/*
    (Preview, Keunggulan, Portofolio) supaya garis terasa "menempel" ke judul.
  --}}
  .solusi-preview h2,
  .solusi-benefits h2,
  .solusi-portfolio h2{
    line-height: 1.25;
  }
  .solusi-preview h2 + hr,
  .solusi-benefits h2 + hr,
  .solusi-portfolio h2 + hr{
    margin-top: 12px;
  }

  /* ===== PORTOFOLIO (Temukan inspirasimu) ===== */
  .solusi-portfolio_grid{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 32px;
  }
  .solusi-portfolio_card{
    display: block;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 14px 30px -18px rgba(0,34,76,.3);
    background: #f4f8ff;
    aspect-ratio: 3 / 4;
  }
  .solusi-portfolio_card img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
  }
  .solusi-portfolio_card:hover img{
    transform: scale(1.05);
  }

  /* Placeholder card yang tampil saat belum ada portofolio diinput admin */
  .solusi-portfolio_placeholder{
    display: flex;
    align-items: center;
    justify-content: center;
    background: repeating-linear-gradient(135deg, #eef3fb, #eef3fb 10px, #e4ecf9 10px, #e4ecf9 20px);
    color: #b9c6dd;
    font-size: 42px;
    box-shadow: none;
  }
  .solusi-portfolio_grid--empty{
    margin-bottom: 8px;
  }

  @media (max-width: 767px){
    .solusi-hero{ padding-top: 36px; }
    .solusi-hero_inner{ text-align: center; justify-content: center; }
    .solusi-hero_text{ max-width: 100%; }
    .solusi-portfolio_grid{ grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 520px){
    .solusi-portfolio_grid{ grid-template-columns: 1fr; }
  }

  {{--
    ===== LIGHTBOX (overview gambar portofolio) =====
    Disalin dari public/css/portfolio-theme.css (dipakai juga di
    portfolio.blade.php & home.blade.php) supaya tema popup-nya konsisten,
    tanpa perlu memuat seluruh file portfolio-theme.css (yang juga berisi
    style grid/filter portofolio yang tidak dipakai di halaman /solusi/*).
  --}}
  .portfolio-lightbox{
    position: fixed;
    inset: 0;
    background: rgba(0, 12, 28, .92);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity .25s ease;
  }
  .portfolio-lightbox.is-open{
    opacity: 1;
    visibility: visible;
  }
  .portfolio-lightbox_track{
    display: flex;
    width: 100%;
    height: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    cursor: grab;
  }
  .portfolio-lightbox_track::-webkit-scrollbar{
    display: none;
  }
  .portfolio-lightbox_track.is-dragging{
    cursor: grabbing;
    scroll-snap-type: none;
  }
  .portfolio-lightbox_slide{
    flex: 0 0 100%;
    max-width: 100%;
    scroll-snap-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    padding: 40px 84px;
  }
  .portfolio-lightbox_slide img{
    max-width: 100%;
    max-height: 78vh;
    border-radius: 12px;
    box-shadow: 0 30px 60px -20px rgba(0,0,0,.6);
    pointer-events: none;
    -webkit-user-select: none;
    user-select: none;
  }
  .portfolio-lightbox_caption{
    color: #fff;
    margin-top: 16px;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
  }
  .portfolio-lightbox_close{
    position: absolute;
    top: 24px;
    right: 24px;
    background:
      linear-gradient(180deg, rgba(255,255,255,.2) 0%, rgba(255,255,255,.02) 45%, rgba(255,255,255,.05) 100%) no-repeat,
      rgba(255,255,255,.04);
    -webkit-backdrop-filter: blur(24px) saturate(200%) brightness(1.05);
    backdrop-filter: blur(24px) saturate(200%) brightness(1.05);
    border: 1px solid rgba(255,255,255,.22);
    color: #fff;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 20px -8px rgba(0,0,0,.45), inset 0 1.5px 0 rgba(255,255,255,.4), inset 0 -1px 0 rgba(0,0,0,.12);
    transition: background .25s ease, box-shadow .25s ease, transform .25s cubic-bezier(.22,1,.36,1);
  }
  .portfolio-lightbox_close:hover{
    background:
      linear-gradient(180deg, rgba(255,255,255,.26) 0%, rgba(255,255,255,.03) 45%, rgba(255,255,255,.07) 100%) no-repeat,
      rgba(255,255,255,.08);
    transform: scale(1.06);
  }
  .portfolio-lightbox_nav{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background:
      linear-gradient(180deg, rgba(255,255,255,.2) 0%, rgba(255,255,255,.02) 45%, rgba(255,255,255,.05) 100%) no-repeat,
      rgba(255,255,255,.04);
    -webkit-backdrop-filter: blur(24px) saturate(200%) brightness(1.05);
    backdrop-filter: blur(24px) saturate(200%) brightness(1.05);
    border: 1px solid rgba(255,255,255,.22);
    color: #fff;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 20px -8px rgba(0,0,0,.45), inset 0 1.5px 0 rgba(255,255,255,.4), inset 0 -1px 0 rgba(0,0,0,.12);
    transition: background .25s ease, box-shadow .25s ease, transform .25s cubic-bezier(.22,1,.36,1);
  }
  .portfolio-lightbox_nav:hover{
    background:
      linear-gradient(180deg, rgba(255,255,255,.26) 0%, rgba(255,255,255,.03) 45%, rgba(255,255,255,.07) 100%) no-repeat,
      rgba(255,255,255,.08);
    transform: translateY(-50%) scale(1.08);
  }
  .portfolio-lightbox_nav svg{
    pointer-events: none;
  }
  .portfolio-lightbox_nav--prev{ left: 20px; }
  .portfolio-lightbox_nav--next{ right: 20px; }
  .portfolio-lightbox_nav[hidden]{ display: none; }
  @media (max-width: 640px){
    .portfolio-lightbox_slide{
      padding: 20px 16px;
    }
    /* Tombol panah tetap tampil di mobile, cuma diperkecil & digeser lebih
       ke tepi supaya tidak menutupi gambar dan tetap gampang di-tap. */
    .portfolio-lightbox_nav{
      width: 38px;
      height: 38px;
    }
    .portfolio-lightbox_nav svg{
      width: 20px;
      height: 20px;
    }
    .portfolio-lightbox_nav--prev{ left: 8px; }
    .portfolio-lightbox_nav--next{ right: 8px; }
  }

  {{--
    ===== FAQ =====
    Markup & style disamakan persis dengan section FAQ di landing page
    (.faq-section/.faq-list/.faq-item di home.blade.php) supaya konsisten;
    CSS-nya disalin ke sini (bukan cukup include file CSS lain) karena
    halaman /solusi/* tidak memuat inline <style> milik home.blade.php.
  --}}
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
</style>
