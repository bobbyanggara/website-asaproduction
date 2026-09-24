{{--
  Breadcrumb navigasi, dipakai di semua halaman publik kecuali beranda.

  Cara pakai (taruh persis setelah @include('partials.site-nav'), sebelum masthead):

    @include('partials.breadcrumb', ['items' => [
      ['label' => 'Portofolio', 'i18n' => 'nav.portfolio'],
    ]])

  - Item pertama otomatis "Beranda" (link ke route('home')), tidak perlu ditulis di $items.
  - Item terakhir di $items dianggap halaman aktif: tidak dibuat link.
  - 'i18n' opsional: isi kalau labelnya ada di kamus SITE_I18N (public/js/i18n-data.js)
    supaya ikut berubah saat bahasa diganti. Kosongkan untuk teks dinamis (mis. judul artikel).
  - 'label_en' opsional: versi Inggris untuk label dinamis dari database (mis. judul artikel);
    dirender dua bahasa lewat @bipair, jadi ikut berganti saat bahasa diubah.
  - 'url' opsional: isi kalau item BUKAN halaman aktif tapi tetap perlu link (mis. "Blog"
    di breadcrumb halaman detail artikel).

  Style di bawah SENGAJA ditanam langsung di partial ini (bukan di
  public/css/blog-theme.css) — pola yang sama dengan
  partials/site-nav.blade.php — supaya cuma ada satu sumber definisi
  untuk komponen ini, dipakai identik di semua halaman.
--}}
<style>
  /* .site-nav bersifat position:fixed (lihat partials/site-nav.blade.php),
     jadi breadcrumb butuh margin-top sendiri supaya tidak tenggelam/
     tertutup navbar yang melayang di atasnya (breadcrumb adalah elemen
     pertama di halaman, sebelum masthead). */
  .breadcrumb-nav{
    margin-top: 84px;
    background: #fff;
    border-bottom: 1px solid #e9ecf2;
    box-shadow: 0 1px 2px rgba(15, 23, 42, .03);
    position: relative;
    z-index: 1;
  }
  .breadcrumb-nav .container{
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    gap: 8px;
    padding: 13px 20px;
    font-size: 14.5px;
    font-weight: 500;
    color: #8a93a6;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  .breadcrumb-nav .container::-webkit-scrollbar{ display: none; }
  .breadcrumb-nav a{
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex: 0 0 auto;
    color: #5b6472;
    text-decoration: none;
    white-space: nowrap;
    transition: color .2s ease;
  }
  .breadcrumb-nav a:hover,
  .breadcrumb-nav a:focus-visible{
    color: var(--brand-light);
  }
  .breadcrumb-home-icon{
    flex: 0 0 auto;
    display: block;
  }
  .breadcrumb-sep{
    display: inline-flex;
    align-items: center;
    flex: 0 0 auto;
    color: #c7cbd4;
  }
  .breadcrumb-current{
    flex: 0 0 auto;
    color: var(--brand-dark);
    font-weight: 600;
    white-space: nowrap;
  }
  @media (max-width: 767px){
    .breadcrumb-nav{ margin-top: 70px; }
    .breadcrumb-nav .container{
      padding: 11px 16px;
      font-size: 13px;
      gap: 6px;
    }
  }
</style>
<nav class="breadcrumb-nav" aria-label="Breadcrumb">
  <div class="container">
    <a href="{{ route('home') }}" data-i18n="nav.home">
      <svg class="breadcrumb-home-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3.5 10.5L12 3.5l8.5 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M5.5 9.5V19a1.2 1.2 0 0 0 1.2 1.2H9.7v-5.4h4.6v5.4h3a1.2 1.2 0 0 0 1.2-1.2V9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span>Beranda</span>
    </a>
    @foreach($items as $item)
      <span class="breadcrumb-sep" aria-hidden="true">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
          <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      @if(!empty($item['url']) && !$loop->last)
        <a href="{{ $item['url'] }}"@if(!empty($item['i18n'])) data-i18n="{{ $item['i18n'] }}"@endif><span>{{ $item['label'] }}</span></a>
      @else
        <span class="breadcrumb-current" aria-current="page"@if(!empty($item['i18n'])) data-i18n="{{ $item['i18n'] }}"@endif>@if(!empty($item['label_en'])) @bipair($item['label'], $item['label_en']) @else {{ $item['label'] }} @endif</span>
      @endif
    @endforeach
  </div>
</nav>
