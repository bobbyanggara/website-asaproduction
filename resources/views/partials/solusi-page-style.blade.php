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
    Kartu ini secara konsep sama seperti "Services Feature Cards"
    (.well__ins1 .grid_4) di home.blade.php — jadi interaksi hover-nya
    disamakan: sapuan gradient brand + ikon jadi putih (invert), bukan cuma
    translateY+shadow polos seperti sebelumnya. Transition & timing (.35s
    ease) juga disamakan persis dengan punya home.

    Catatan tinggi kartu: .row/.grid_4 di sini masih grid lama berbasis
    float (lihat grid.css), bukan flexbox — jadi height:100% pada
    .solusi-benefit-card saja tidak cukup untuk menyamakan tinggi antar
    kartu kalau panjang teksnya beda. .solusi-benefits .row di-override
    jadi flex khusus di section ini saja (tidak menyentuh .row global)
    supaya kartu benar-benar sejajar/simetris, konsisten dengan kesan
    "equal height" yang sama seperti section Layanan di halaman utama.

    Lebar kolom: .grid_4 dari grid.css punya width FIXED per breakpoint
    (mis. 370px/300px/236px) + margin-left tetap. Kalau cuma ditambah
    display:flex tanpa mengubah width itu, sisa ruang kosong di container
    menumpuk di satu sisi (biasanya kanan) dan gap antar kartu jadi tidak
    rata — kartu tengah/kanan terlihat "kepepet". Makanya width bawaan
    grid_4 ditimpa jadi flex:1 (equal-width) di section ini saja, supaya
    3 kartu benar-benar membagi rata lebar container dengan gap simetris.
  --}}
  .solusi-benefits .row{
    margin-top: 32px;
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    margin-left: 0;
    gap: 30px;
  }
  .solusi-benefits .row > [class*="grid_"]{
    display: flex;
    float: none;
    width: auto;
    flex: 1 1 260px;
    margin-left: 0;
  }
  .solusi-benefit-card{
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    background: #fff;
    border: 1px solid #e9ecf2;
    border-radius: 14px;
    padding: 34px 26px;
    text-align: center;
    transition: background .35s ease, box-shadow .35s ease, transform .35s ease, border-color .35s ease;
  }
  .solusi-benefit-card:hover{
    transform: translateY(-6px);
    background: linear-gradient(135deg, var(--brand-accent), var(--brand-light) 55%, var(--brand-dark));
    border-color: transparent;
    box-shadow: 0 20px 40px -22px rgba(0,34,76,.35);
  }
  .solusi-benefit-card_icon{
    width: 64px;
    height: 64px;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: #eaf2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .35s ease;
  }
  .solusi-benefit-card:hover .solusi-benefit-card_icon{
    background: rgba(255,255,255,.16);
  }
  .solusi-benefit-card_icon img{
    width: 34px;
    height: 34px;
    object-fit: contain;
    transition: filter .35s ease;
  }
  .solusi-benefit-card:hover .solusi-benefit-card_icon img{
    filter: brightness(0) invert(1) drop-shadow(0 2px 6px rgba(0,0,0,.15));
  }
  .solusi-benefit-card_icon i{
    font-size: 26px;
    color: var(--brand-light);
    transition: color .35s ease;
  }
  .solusi-benefit-card:hover .solusi-benefit-card_icon i{
    color: #fff;
  }
  .solusi-benefit-card h3{
    margin: 0 0 10px;
    font-size: 18px;
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
  .solusi-benefit-card:hover p{
    color: #fff;
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
