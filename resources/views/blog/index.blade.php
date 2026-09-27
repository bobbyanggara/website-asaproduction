<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
    $siteName = $setting->site_title ?? 'Asa Production';
    $canonicalUrl = url()->current();
  @endphp

  <title>Blog - {{ $siteName }}</title>
  <meta name="description" content="Kumpulan artikel dan insight seputar produksi kreatif dari {{ $siteName }}.">
  <link rel="canonical" href="{{ $canonicalUrl }}">
  <meta name="robots" content="index, follow">
  @if($posts->previousPageUrl())
  <link rel="prev" href="{{ $posts->previousPageUrl() }}">
  @endif
  @if($posts->nextPageUrl())
  <link rel="next" href="{{ $posts->nextPageUrl() }}">
  @endif

  {{-- Open Graph --}}
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ $siteName }}">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="Blog - {{ $siteName }}">
  <meta property="og:description" content="Kumpulan artikel dan insight seputar produksi kreatif dari {{ $siteName }}.">
  <meta property="og:url" content="{{ $canonicalUrl }}">

  {{-- Data terstruktur (schema.org): daftar artikel blog dari {{ $siteName }}. --}}
  <script type="application/ld+json">
  {!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
      [
        '@type' => 'Blog',
        'name' => 'Blog '.$siteName,
        'url' => $canonicalUrl,
        'publisher' => ['@type' => 'Organization', 'name' => $siteName],
        'blogPost' => $posts->map(fn ($p) => [
          '@type' => 'BlogPosting',
          'headline' => $p->title,
          'url' => route('blog.show', $p->slug),
          'datePublished' => optional($p->published_at)->toAtomString(),
        ])->values()->all(),
      ],
      [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
          ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
          ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $canonicalUrl],
        ],
      ],
    ],
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : asset('images/favicon.ico') }}" type="image/x-icon">

  {{-- Stylesheet yang sama dengan landing page, agar warna, font (Ubuntu) dan komponen (navbar, tombol, footer) konsisten --}}
  <link rel="stylesheet" href="{{ asset('css/grid.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/site-nav-shared.css') }}">
  <link rel="stylesheet" href="{{ asset('css/blog-theme.css') }}">
</head>
<body class="blog-body">

  @include('partials.site-nav')

  @include('partials.breadcrumb', ['items' => [
    ['label' => 'Blog', 'i18n' => 'nav.blog'],
  ]])

  {{-- ======================== MASTHEAD ======================== --}}
  <div class="blog-masthead">
    <div class="container">
      <span class="eyebrow" data-i18n="blogIndex.eyebrow">Blog</span>
      <h1 data-i18n="blogIndex.mastheadTitle">Artikel &amp; Insight</h1>
      <p data-i18n="blogIndex.mastheadDesc" data-i18n-var-site="{{ $setting->site_title ?? 'Asa Production' }}">Tips, cerita di balik layar, dan wawasan seputar dunia produksi kreatif dari {{ $setting->site_title ?? 'Asa Production' }}.</p>
    </div>
  </div>

  {{-- ======================== POST GRID ======================== --}}
  @if($posts->count())
  <div class="blog-grid">
    @foreach($posts as $post)
    <a href="{{ route('blog.show', $post->slug) }}" class="post-card">
      <div class="post-card_thumb">
        @if($post->thumbnail)
          <img src="{{ asset('storage/'.$post->thumbnail) }}" alt="{{ $post->title }}" loading="lazy">
        @endif
      </div>
      <div class="post-card_body">
        <div class="post-card_meta">
          <span class="post-card_date">{{ $post->author ?: 'Admin' }} &bull; {{ optional($post->published_at)->translatedFormat('d M Y, H:i') }}</span>
          <span class="post-card_likes" data-likes-for="{{ $post->slug }}">
            <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
            {{ $post->likes }}
          </span>
        </div>
        <h2 class="post-card_title">@bi($post, 'title')</h2>
        <p class="post-card_excerpt">@biexcerpt($post, 120)</p>
        <div class="post-card_footer">
          <span class="post-card_readmore" data-i18n="blogIndex.readMore">Baca selengkapnya &rarr;</span>
          <span class="post-card_readtime">
            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
            {{ $readingMinutes[$post->id] ?? 1 }} <span data-i18n="blogIndex.minRead">menit</span>
          </span>
        </div>
      </div>
    </a>
    @endforeach
  </div>
  <div class="pagination-wrap">{{ $posts->links() }}</div>
  @else
  <div class="blog-empty" data-i18n="blogIndex.empty">Belum ada artikel yang dipublikasikan.</div>
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
