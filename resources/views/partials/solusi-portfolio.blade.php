{{--
  Section "Temukan inspirasimu" — grid portofolio ringkas (maks 3 item,
  sudah difilter per kategori dari controller). Dipakai di semua halaman
  /solusi/*. Perlu variabel $portfolios (Collection<Portfolio>).
--}}
<section class="well solusi-portfolio center" id="portofolio">
  <div class="container">
    <h2 class="section-heading" data-i18n="solusi.portfolioTitle">Temukan inspirasimu</h2>
    <hr class="section-heading_bar"/>
    @if($portfolios->count())
      <div class="solusi-portfolio_grid">
        @foreach($portfolios as $item)
          {{-- Animasi sendiri (wow fadeInUp, delay per index), sengaja dipisah
               dari section galeri portofolio di halaman beranda (yang pakai
               "item wow zoomIn") — dua section beda konteks/markup, jadi
               classnya juga beda meskipun sama-sama pakai wow.js.

               data-full & data-title dipakai portfolio-lightbox.js untuk
               membuka overview gambar (bukan navigasi ke halaman storage).
               href tetap diisi sebagai fallback kalau JS gagal dimuat. --}}
          <a class="solusi-portfolio_card wow fadeInUp" data-wow-delay="{{ 0.1 * $loop->index }}s" data-wow-duration="0.8s" href="{{ asset('storage/'.($item->image ?: $item->thumbnail)) }}" data-full="{{ asset('storage/'.($item->image ?: $item->thumbnail)) }}" data-title="{{ $item->title }}" title="{{ $item->title }}">
            <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" loading="lazy">
          </a>
        @endforeach
      </div>
    @else
      {{-- Belum ada data portofolio untuk grup solusi ini — tampilkan
           placeholder alih-alih menyembunyikan section, biar layout halaman
           tidak terasa bolong sebelum admin menambahkan portofolio. --}}
      <div class="solusi-portfolio_grid solusi-portfolio_grid--empty">
        @for($i = 0; $i < 3; $i++)
          <div class="solusi-portfolio_card solusi-portfolio_placeholder" aria-hidden="true">
            <i class="fa fa-image"></i>
          </div>
        @endfor
      </div>
      <div class="blog-empty" data-i18n="solusiPortfolio.empty">Belum ada portofolio yang dipublikasikan untuk layanan ini.</div>
    @endif
    <div class="center" style="margin-top:36px;">
      <a href="{{ route('portfolio.index') }}" class="btn btn-sm" data-i18n="solusi.ctaViewAllPortfolio">Lihat Semua Portofolio</a>
    </div>
  </div>
</section>

@if($portfolios->count())
  {{--
    Markup lightbox sama persis dengan portfolio.blade.php & home.blade.php
    (tema/style dari .portfolio-lightbox* di partials.solusi-page-style,
    disalin dari public/css/portfolio-theme.css) supaya konsisten. Script
    js/portfolio-lightbox.js (dimuat di masing-masing halaman /solusi/*)
    yang mem-binding klik pada elemen [data-full] di atas ke popup ini.
  --}}
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
  <script src="{{ asset('js/portfolio-lightbox.js') }}"></script>
@endif
