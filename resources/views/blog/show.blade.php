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
    $ogImage = $post->thumbnail ? asset('storage/'.$post->thumbnail) : ($setting->logo ? asset('storage/'.$setting->logo) : asset('images/favicon.ico'));
    $publishedIso = optional($post->published_at)->toAtomString();
    $modifiedIso = optional($post->updated_at)->toAtomString();
  @endphp

  <title>{{ $post->title }} - {{ $siteName }}</title>
  <meta name="description" content="{{ $post->meta_description }}">
  <link rel="canonical" href="{{ $canonicalUrl }}">
  <meta name="robots" content="index, follow, max-image-preview:large">
  <meta name="author" content="{{ $post->author ?: $siteName }}">

  {{-- Open Graph --}}
  <meta property="og:type" content="article">
  <meta property="og:site_name" content="{{ $siteName }}">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="{{ $post->title }}">
  <meta property="og:description" content="{{ $post->meta_description }}">
  <meta property="og:url" content="{{ $canonicalUrl }}">
  <meta property="og:image" content="{{ $ogImage }}">
  @if($publishedIso)
  <meta property="article:published_time" content="{{ $publishedIso }}">
  @endif
  @if($modifiedIso)
  <meta property="article:modified_time" content="{{ $modifiedIso }}">
  @endif
  <meta property="article:author" content="{{ $post->author ?: $siteName }}">
  <meta property="article:publisher" content="{{ $siteName }}">

  {{-- Twitter Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $post->title }}">
  <meta name="twitter:description" content="{{ $post->meta_description }}">
  <meta name="twitter:image" content="{{ $ogImage }}">

  {{-- Data terstruktur (schema.org) — bantu Google memahami ini artikel blog
       dari Asa Production, siapa penulisnya dan kapan dipublikasikan, supaya
       lebih berpeluang tampil sebagai rich result / article carousel. --}}
  <script type="application/ld+json">
  {!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
      [
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonicalUrl],
        'headline' => $post->title,
        'description' => $post->meta_description,
        'image' => [$ogImage],
        'datePublished' => $publishedIso,
        'dateModified' => $modifiedIso ?: $publishedIso,
        'inLanguage' => 'id-ID',
        'author' => [
          '@type' => 'Person',
          'name' => $post->author ?: $siteName,
        ],
        'publisher' => [
          '@type' => 'Organization',
          'name' => $siteName,
          'logo' => [
            '@type' => 'ImageObject',
            'url' => $setting->logo ? asset('storage/'.$setting->logo) : asset('images/favicon.ico'),
          ],
        ],
      ],
      [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
          ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => route('home')],
          ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
          ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => $canonicalUrl],
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
    ['label' => 'Blog', 'i18n' => 'nav.blog', 'url' => route('blog.index')],
    ['label' => $post->title, 'label_en' => $post->title_en],
  ]])

  <article>
    {{-- ======================== ARTICLE HEADER ======================== --}}
    <div class="article-masthead">
      <div class="container">
        <a href="{{ route('blog.index') }}" class="back-link">&larr; <span data-i18n="blogShow.backToBlog">Kembali ke Blog</span></a>
        <div class="post-meta-row">
          <span data-i18n="blogShow.byLine">Oleh</span> {{ $post->author ?: 'Admin' }} &bull;
          <time datetime="{{ optional($post->published_at)->toAtomString() }}">{{ optional($post->published_at)->translatedFormat('d F Y, H:i') }}</time>
          <span class="post-reading-time">
            &bull;
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
            {{ $readingMinutes }} <span data-i18n="blogShow.minRead">menit baca</span>
          </span>
        </div>
        <h1>@bi($post, 'title')</h1>
      </div>
    </div>

    @if($post->thumbnail)
    <div class="article-cover">
      <img src="{{ asset('storage/'.$post->thumbnail) }}" alt="{{ $post->title }}">
    </div>
    @endif

    {{-- ======================== SHARE & LIKES ======================== --}}
    @php($shareUrl = $canonicalUrl)
    <div class="share-likes">
      <div class="share-likes_share">
        <span class="share-likes_label" data-i18n="blogShow.shareLabel">Bagikan</span>
        <div class="share-buttons">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-btn share-btn--facebook" aria-label="Bagikan ke Facebook" data-i18n-aria-label="blogShow.shareFacebook">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor" aria-hidden="true"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.9h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94Z"/></svg>
          </a>
          <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title.' - '.$shareUrl) }}" target="_blank" rel="noopener" class="share-btn share-btn--whatsapp" aria-label="Bagikan ke WhatsApp" data-i18n-aria-label="blogShow.shareWhatsapp">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor" aria-hidden="true"><path d="M12.02 2C6.5 2 2 6.48 2 12c0 1.77.46 3.45 1.34 4.94L2 22l5.2-1.36A9.96 9.96 0 0 0 12.02 22C17.53 22 22 17.52 22 12S17.53 2 12.02 2Zm0 18.06c-1.63 0-3.2-.44-4.56-1.27l-.33-.2-3.09.81.83-3-.21-.32a8.05 8.05 0 0 1-1.24-4.28c0-4.46 3.63-8.08 8.6-8.08 4.6 0 8.34 3.62 8.34 8.08 0 4.46-3.74 8.26-8.34 8.26Zm4.53-6.04c-.25-.12-1.47-.72-1.7-.8-.23-.08-.4-.12-.56.12-.17.25-.65.8-.8.96-.15.17-.29.19-.54.06-.25-.12-1.06-.39-2.01-1.23-.74-.66-1.24-1.48-1.39-1.73-.14-.25-.02-.38.11-.5.11-.11.25-.29.37-.44.12-.15.16-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.36-.77-1.86-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.08 0 1.23.89 2.42 1.02 2.59.12.17 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.55.1.47-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28Z"/></svg>
          </a>
          <a href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&amp;text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="share-btn share-btn--telegram" aria-label="Bagikan ke Telegram" data-i18n-aria-label="blogShow.shareTelegram">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor" aria-hidden="true"><path d="M21.9 4.3 18.6 20c-.25 1.1-.9 1.37-1.83.86l-5.05-3.72-2.44 2.34c-.27.27-.5.5-1.02.5l.36-5.14 9.35-8.44c.41-.36-.09-.56-.63-.2L6.06 12.9l-4.98-1.55c-1.08-.34-1.1-1.08.23-1.6L20.5 3.06c.9-.33 1.68.2 1.4 1.24Z"/></svg>
          </a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-btn share-btn--linkedin" aria-label="Bagikan ke LinkedIn" data-i18n-aria-label="blogShow.shareLinkedin">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.9 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.24 8.25h4.5V23H.24V8.25ZM8.24 8.25h4.31v2.02h.06c.6-1.13 2.07-2.32 4.26-2.32 4.56 0 5.4 3 5.4 6.9V23h-4.5v-6.98c0-1.66-.03-3.8-2.32-3.8-2.32 0-2.68 1.81-2.68 3.68V23h-4.5V8.25Z"/></svg>
          </a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&amp;text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="share-btn share-btn--x" aria-label="Bagikan ke X" data-i18n-aria-label="blogShow.shareX">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M18.24 2h3.3l-7.2 8.23L23 22h-6.9l-5.4-7.06L4.5 22H1.2l7.7-8.8L1 2h7.06l4.88 6.46L18.24 2Zm-1.16 18h1.83L7.02 3.9H5.06L17.08 20Z"/></svg>
          </a>
          <button type="button" class="share-btn share-btn--copy" id="copyLinkBtn" data-url="{{ $shareUrl }}" data-tooltip="Salin Link" data-i18n-tooltip="blogShow.copyLinkTooltip" aria-label="Salin tautan artikel" data-i18n-aria-label="blogShow.copyLink">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.07 0l2.83-2.83a5 5 0 0 0-7.07-7.07L11.5 4.5"/><path d="M14 11a5 5 0 0 0-7.07 0l-2.83 2.83a5 5 0 0 0 7.07 7.07L12.5 19.5"/></svg>
          </button>
        </div>
      </div>

      <button type="button" class="like-btn" id="likeBtn" data-url="{{ route('blog.like', $post->slug) }}">
        <svg class="like-btn_icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
        <span id="likeCount">{{ $post->likes }}</span> <span data-i18n="blogShow.likeLabel">Suka</span>
      </button>
    </div>

    {{-- ======================== TABLE OF CONTENTS ======================== --}}
    {{-- Diisi otomatis oleh public/js/blog-toc.js dari heading (H2/H3) di dalam
         .article-content — disembunyikan lewat CSS kalau artikel tidak punya
         heading (mis. artikel lama format teks polos). --}}
    <nav class="article-toc" id="articleToc" aria-label="Daftar isi artikel">
      <button type="button" class="article-toc_toggle" id="articleTocToggle" aria-expanded="true">
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        <span data-i18n="blogShow.tocTitle">Daftar Isi</span>
      </button>
      <ol class="article-toc_list" id="articleTocList"></ol>
    </nav>

    {{-- ======================== CONTENT ======================== --}}
    <div class="article-content" id="articleContent">
      @bibody($post, 'content')
    </div>

    {{-- Byline penutup — menegaskan sumber artikel untuk pembaca & mesin pencari. --}}
    <div class="article-byline">
      <div class="article-byline_avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($post->author ?: 'A', 0, 1)) }}</div>
      <div class="article-byline_body">
        <div class="article-byline_name">{{ $post->author ?: 'Admin' }}</div>
        <div class="article-byline_role" data-i18n="blogShow.bylineRole" data-i18n-var-site="{{ $siteName }}">Tim {{ $siteName }}</div>
      </div>
    </div>
  </article>

  {{-- ======================== RELATED POSTS ======================== --}}
  @if($related->count())
  <div class="related-wrap">
    <h3 data-i18n="blogShow.relatedTitle">Artikel Lainnya</h3>
    <div class="related-grid">
      @foreach($related as $r)
        <a href="{{ route('blog.show', $r->slug) }}" class="related-card">
          <div class="related-card_thumb">
            @if($r->thumbnail)
              <img src="{{ asset('storage/'.$r->thumbnail) }}" alt="{{ $r->title }}" loading="lazy">
            @endif
          </div>
          <div class="related-card_body">
            <span class="related-card_title">@bi($r, 'title')</span>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  @include('partials.site-footer')

  <script src="{{ asset('js/i18n-data.js') }}"></script>
  <script src="{{ asset('js/i18n-lib.js') }}"></script>
  <script src="{{ asset('js/site-nav.js') }}"></script>
  <script src="{{ asset('js/blog-like.js') }}"></script>
  <script src="{{ asset('js/blog-toc.js') }}"></script>
  <script>
    {{-- site-nav.js sudah memanggil I18n.init & bindLangSwitcher;
         di sini cukup expose helper yang dipakai chatbot widget --}}
    window.__siteLang = function () { return window.I18n ? window.I18n.getLang() : 'id'; };
    window.__t = function (key) { return window.I18n ? window.I18n.t(key) : key; };
  </script>

  @include('partials.chatbot-widget')
</body>
</html>
