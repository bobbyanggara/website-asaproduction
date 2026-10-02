{{-- Footer — sama persis dengan footer landing page (resources/views/home.blade.php).
     Link jangkar (#about, #services, dst) diarahkan kembali ke route('home') karena
     halaman ini bukan halaman utama.

     Catatan: background gambar ditulis juga lewat inline style (bukan cuma data-url),
     karena halaman ini (kontak/blog/about) tidak memuat jQuery + jquery.rd-parallax.js
     seperti landing page. Kalau cuma andalkan data-url + JS, gambarnya nggak pernah
     muncul di sini — makanya sebelumnya footer di halaman ini keliatan polos navy saja. --}}
<style>
  /* Footer brand font: samakan dengan navbar (League Spartan).
     Selector dibuat lebih spesifik (footer#contact ...) supaya menang
     lawan aturan font di style.css tema. text-transform:none dipaksa
     juga karena tema aslinya bikin teks ini jadi KAPITAL SEMUA
     ("ASA PRODUCTION"), padahal navbar tampil normal ("Asa Production"). */
  footer#contact .footer-brand span{
    font-family: 'League Spartan', sans-serif !important;
    text-transform: none !important;
  }
</style>
<footer id="contact">
  @php($footerBgUrl = $setting->footer_bg ? asset('storage/'.$setting->footer_bg) : asset('images/parallax2.jpg'))
  <div class="parallax" data-url="{{ $footerBgUrl }}" data-mobile="true" data-speed="0.5 " data-direction="inverted" style="background-image:url('{{ $footerBgUrl }}');background-size:cover;background-position:center center;">
    <div class="well4">
      <div class="container center">
        <hr/>
        <h2>@bi($setting, 'footer_title')</h2>
        <h3>@bi($setting, 'footer_subtitle')</h3>
        <a class="btn" href="{{ $setting->footer_cta_link ?: 'https://wa.me/'.$setting->whatsapp_number }}">@bi($setting, 'footer_cta_text')</a>
      </div>
    </div>
  </div>

  {{-- FOOTER COLUMNS --}}
  <div class="footer-columns">
    <div class="container">
      <div class="row">
        <div class="grid_3 footer-col footer-col--brand">
          <a href="{{ route('home') }}" class="footer-brand">
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
            @include('partials.footer-solutions')
          </ul>
        </div>

        <div class="grid_2 footer-col footer-col--company">
          <h4 data-i18n="footer.companyTitle">Perusahaan</h4>
          <ul class="footer-links">
            <li><a href="{{ route('about') }}" data-i18n="footer.aboutUs">Tentang Kami</a></li>
            <li><a href="{{ route('home') }}#services" data-i18n="footer.ourServices">Layanan Kami</a></li>
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
        &copy; <span id="copyright-year">{{ date('Y') }}</span> {{ $setting->site_title }}. <span data-i18n="footer.rights">All Rights Reserved</span>
      </div>
    </div>
  </div>
</footer>
