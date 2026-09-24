{{--
  Section "Temukan inspirasimu" — grid portofolio ringkas (maks 3 item,
  sudah difilter per kategori dari controller). Dipakai di semua halaman
  /solusi/*. Perlu variabel $portfolios (Collection<Portfolio>).
--}}
<section class="well solusi-portfolio center" id="portofolio">
  <div class="container">
    <h2 data-i18n="solusi.portfolioTitle">Temukan inspirasimu</h2>
    <hr/>
    @if($portfolios->count())
      <div class="solusi-portfolio_grid">
        @foreach($portfolios as $item)
          {{-- Animasi sendiri (wow fadeInUp, delay per index), sengaja dipisah
               dari section galeri portofolio di halaman beranda (yang pakai
               "item wow zoomIn") — dua section beda konteks/markup, jadi
               classnya juga beda meskipun sama-sama pakai wow.js. --}}
          <a class="solusi-portfolio_card wow fadeInUp" data-wow-delay="{{ 0.1 * $loop->index }}s" data-wow-duration="0.8s" href="{{ asset('storage/'.($item->image ?: $item->thumbnail)) }}" target="_blank" rel="noopener" title="{{ $item->title }}">
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
