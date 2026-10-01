<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard') - Admin Asa Production</title>
  @php
    $__favicon = \App\Models\Setting::current()->favicon ?? null;
    $solusiGroups = [
      'app-mobile' => ['label' => 'Jasa App Mobile', 'icon' => 'bi-phone-fill'],
      'website' => ['label' => 'Jasa Website', 'icon' => 'bi-globe2'],
      'custom-system' => ['label' => 'Jasa Sistem Kustom', 'icon' => 'bi-diagram-3'],
    ];
    // Halaman "Produk" — dinamis dari tabel products: produk baru yang
    // ditambah admin otomatis muncul di sidebar ini.
    $__sidebarProducts = \App\Models\Product::ordered()->get(['id', 'name', 'slug', 'is_active']);
  @endphp
  <link rel="icon" href="{{ $__favicon ? asset('storage/'.$__favicon) : asset('images/favicon.ico') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    /*========================================================
       Token warna & tipografi disamakan dengan website utama
       (lihat public/css/style.css): brand-dark/mid/light/accent
       + font Ubuntu, supaya admin panel terasa satu identitas
       dengan halaman publik, bukan template generik.
    =========================================================*/
    :root {
      --sidebar-w: 268px;
      --brand-dark: #00224C;
      --brand-mid: #003D8F;
      --brand-light: #0051B5;
      --brand-accent: #2E8BFF;
      --accent: var(--brand-accent);
      --accent-soft: rgba(46,139,255,.14);
      --sidebar-bg: var(--brand-dark);
      --sidebar-bg-soft: var(--brand-mid);
      --sidebar-border: rgba(255,255,255,.08);
      --sidebar-text: #9fb3d6;
      --sidebar-text-active: #ffffff;
      --bg: #F3F6FC;
      --card-border: #E1E8F5;
      --ink: #101B2D;
      --ink-muted: #64748B;
      --radius: 14px;
    }

    * { box-sizing: border-box; }

    html, body { height: 100%; }

    body {
      background: var(--bg);
      font-family: 'Ubuntu', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      color: var(--ink);
      overflow-x: hidden;
    }

    /* ---------- Layout shell ---------- */
    .app-shell { display: flex; min-height: 100vh; }

    /* ---------- Sidebar: fixed, never scrolls with page ---------- */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      height: 100dvh; /* di HP, 100vh ikut tinggi address bar sehingga footer sidebar ketutup */
      background: linear-gradient(195deg, var(--sidebar-bg) 0%, var(--sidebar-bg-soft) 100%);
      color: var(--sidebar-text);
      display: flex;
      flex-direction: column;
      z-index: 1030;
      transition: transform .25s ease;
      border-right: 1px solid var(--sidebar-border);
    }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: 1.5rem 1.25rem 1.25rem;
      border-bottom: 1px solid var(--sidebar-border);
      flex-shrink: 0;
    }
    .sidebar-brand .logo-badge {
      width: 40px; height: 40px;
      border-radius: 11px;
      background: linear-gradient(135deg, var(--brand-accent), var(--brand-light));
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; color: #fff; font-size: 1.05rem;
      flex-shrink: 0;
      box-shadow: 0 6px 16px -4px rgba(46,139,255,.55);
    }
    .sidebar-brand .brand-text { line-height: 1.25; overflow: hidden; }
    .sidebar-brand .brand-text strong { color: #fff; font-size: .95rem; font-weight: 500; display: block; }
    .sidebar-brand .brand-text span { font-size: .72rem; color: var(--sidebar-text); }

    .sidebar-scroll {
      flex: 1 1 auto;
      overflow-y: auto;
      overscroll-behavior: contain; /* scroll di ujung sidebar tidak menggeser halaman utama */
      scroll-behavior: auto;        /* restore posisi harus instan, bukan animasi */
      padding: 1rem .85rem;
      scrollbar-width: thin;
      scrollbar-color: rgba(255,255,255,.15) transparent;
    }
    .sidebar-scroll::-webkit-scrollbar { width: 5px; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 10px; }

    .nav-section-label {
      font-size: .68rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: #6f88b8;
      font-weight: 700;
      padding: .9rem .6rem .4rem;
    }

    {{-- Grup collapsible untuk tiap halaman Solusi (App Mobile/Website/Sistem
         Kustom) — dipakai supaya sub-menu (Konten, Keunggulan, Klien, Layanan,
         Statistik, Proses, Badge Teknologi, FAQ) tidak numpuk flat di sidebar
         dan bikin bingung; ringkas jadi 1 baris per halaman, expand saat perlu. --}}
    .nav-group_toggle {
      cursor: pointer;
      user-select: none;
    }
    .nav-group_toggle .bi-chevron-down {
      margin-left: auto;
      font-size: .7rem;
      transition: transform .2s ease;
      flex-shrink: 0;
    }
    .nav-group_toggle[aria-expanded="true"] .bi-chevron-down {
      transform: rotate(180deg);
    }
    .nav-group_sub {
      padding-left: .55rem;
      border-left: 2px solid rgba(255,255,255,.08);
      margin-left: 1.35rem;
      margin-bottom: .15rem;
    }
    .nav-group_sub .nav-link-item {
      font-size: .82rem;
      padding: .48rem .65rem;
    }
    .nav-group_sub .nav-link-item i {
      font-size: .85rem;
      width: 16px;
    }

    .sidebar a.nav-link-item {
      color: var(--sidebar-text);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: .7rem;
      padding: .62rem .75rem;
      border-radius: 10px;
      font-size: .875rem;
      font-weight: 400;
      margin-bottom: .15rem;
      position: relative;
      transition: background .15s ease, color .15s ease;
    }
    .sidebar a.nav-link-item i { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }
    .sidebar a.nav-link-item .badge-count {
      margin-left: auto;
      background: rgba(255,255,255,.1);
      color: #dbe6fb;
      font-weight: 500;
      font-size: .68rem;
      padding: .15rem .45rem;
      border-radius: 20px;
    }
    .sidebar a.nav-link-item:hover {
      background: rgba(255,255,255,.06);
      color: #fff;
    }
    .sidebar a.nav-link-item.active {
      background: var(--accent-soft);
      color: #fff;
    }
    .sidebar a.nav-link-item.active::before {
      content: '';
      position: absolute;
      left: -0.85rem;
      top: 50%;
      transform: translateY(-50%);
      width: 3px;
      height: 60%;
      background: var(--brand-accent);
      border-radius: 0 4px 4px 0;
    }
    .sidebar a.nav-link-item.active .badge-count {
      background: var(--brand-accent);
      color: #fff;
    }

    .sidebar-footer {
      flex-shrink: 0;
      padding: .9rem .85rem 1.1rem;
      border-top: 1px solid var(--sidebar-border);
    }
    .sidebar-footer a.view-site {
      display: flex; align-items: center; gap: .6rem;
      color: var(--sidebar-text);
      text-decoration: none;
      font-size: .82rem;
      font-weight: 400;
      padding: .55rem .75rem;
      border-radius: 10px;
      margin-bottom: .5rem;
      transition: background .15s ease, color .15s ease;
    }
    .sidebar-footer a.view-site:hover { background: rgba(255,255,255,.06); color: #fff; }
    .sidebar-footer .btn-logout {
      width: 100%;
      background: rgba(239,68,68,.14);
      border: 1px solid rgba(239,68,68,.3);
      color: #fca5a5;
      font-size: .82rem;
      font-weight: 500;
      padding: .55rem .75rem;
      border-radius: 10px;
      transition: background .15s ease;
    }
    .sidebar-footer .btn-logout:hover { background: rgba(239,68,68,.24); color: #fecaca; }

    /* ---------- Main content ---------- */
    .main-content {
      margin-left: var(--sidebar-w);
      width: calc(100% - var(--sidebar-w));
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      position: sticky;
      top: 0;
      z-index: 1020;
      background: rgba(243,246,252,.85);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--card-border);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }
    .topbar h4 { margin: 0; font-weight: 500; font-size: 1.15rem; color: var(--ink); }
    .topbar .breadcrumb-hint { font-size: .78rem; color: var(--ink-muted); margin-top: 2px; }

    .sidebar-toggle-btn {
      display: none;
      border: 1px solid var(--card-border);
      background: #fff;
      border-radius: 10px;
      width: 38px; height: 38px;
      align-items: center;
      justify-content: center;
      color: var(--ink);
    }

    .user-chip {
      display: flex;
      align-items: center;
      gap: .65rem;
      background: #fff;
      border: 1px solid var(--card-border);
      padding: .35rem .8rem .35rem .4rem;
      border-radius: 50px;
    }
    .user-chip .avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--brand-accent), var(--brand-light));
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-weight: 700; font-size: .82rem;
      flex-shrink: 0;
    }
    .user-chip .info { line-height: 1.15; }
    .user-chip .info strong { display: block; font-size: .82rem; color: var(--ink); font-weight: 500; }
    .user-chip .info span { font-size: .7rem; color: var(--ink-muted); }

    .content-area {
      padding: 1.75rem 2rem 3rem;
      flex: 1;
    }

    /* ---------- Shared component styles used across admin pages ---------- */
    .card {
      border: 1px solid var(--card-border);
      border-radius: var(--radius);
      box-shadow: 0 1px 2px rgba(0,34,76,.05);
    }
    .card-header {
      border-bottom: 1px solid var(--card-border);
      font-weight: 500;
      color: var(--ink);
    }
    .btn-dark {
      background: var(--brand-dark);
      border-color: var(--brand-dark);
      font-weight: 500;
      border-radius: 10px;
    }
    .btn-dark:hover { background: var(--brand-accent); border-color: var(--brand-accent); }
    .btn-outline-secondary, .btn-outline-danger { border-radius: 10px; font-weight: 400; }
    .table thead th {
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: var(--ink-muted);
      font-weight: 700;
      border-bottom-width: 1px;
    }
    .form-control, .form-select {
      border-radius: 10px;
      border-color: var(--card-border);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--brand-accent);
      box-shadow: 0 0 0 .2rem var(--accent-soft);
    }
    .alert { border-radius: 12px; border: none; }
    .thumb-preview { width:60px; height:60px; object-fit:cover; border-radius:.6rem; }

    /* ---------- Mobile ---------- */
    @media (max-width: 991.98px) {
      .sidebar { transform: translateX(-100%); box-shadow: 0 0 40px rgba(0,34,76,.35); }
      .sidebar.show { transform: translateX(0); }
      .main-content { margin-left: 0; width: 100%; }
      .sidebar-toggle-btn { display: inline-flex; }
      .sidebar-backdrop {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,17,38,.5);
        z-index: 1025;
      }
      .sidebar-backdrop.show { display: block; }
      .topbar { padding: .85rem 1.1rem; }
      .content-area { padding: 1.25rem 1.1rem 2.5rem; }
      .user-chip .info { display: none; }
    }

    /* ---------- Tombol "Tambah" modern + popup (dipakai di SEMUA halaman admin) ---------- */
    .btn-add{
      display: inline-flex; align-items: center; gap: 10px; border: 0; cursor: pointer; text-decoration: none;
      padding: 6px 20px 6px 6px; border-radius: 999px; color: #fff; font-weight: 600; font-size: .9rem; line-height: 1;
      background: linear-gradient(135deg, var(--brand-accent), var(--brand-light));
      box-shadow: 0 10px 22px -10px rgba(0,81,181,.75), inset 0 1px 0 rgba(255,255,255,.3);
      transition: transform .18s ease, box-shadow .18s ease;
    }
    .btn-add:hover{ color: #fff; transform: translateY(-2px); box-shadow: 0 14px 26px -10px rgba(0,81,181,.85), inset 0 1px 0 rgba(255,255,255,.3); }
    .btn-add:active{ transform: scale(.97); }
    .btn-add .plus{ display: grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; background: rgba(255,255,255,.2); font-size: 1.1rem; transition: transform .25s ease; }
    .btn-add:hover .plus{ transform: rotate(90deg); }
    .btn-add--sm{ padding: 4px 14px 4px 4px; font-size: .8rem; gap: 8px; }
    .btn-add--sm .plus{ width: 26px; height: 26px; font-size: .9rem; }

    dialog.app-dialog{ border: 0; border-radius: 20px; padding: 0; width: min(520px, calc(100% - 28px)); max-height: calc(100vh - 40px); overflow: auto; color: var(--ink); box-shadow: 0 30px 70px -20px rgba(0,20,50,.55); }
    dialog.app-dialog[open]{ animation: appDialogIn .22s cubic-bezier(.22,1,.36,1); }
    dialog.app-dialog::backdrop{ background: rgba(0,20,46,.5); backdrop-filter: blur(4px); }
    @keyframes appDialogIn{ from{ opacity: 0; transform: translateY(10px) scale(.97); } to{ opacity: 1; transform: none; } }
    .app-dialog_head{ display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid var(--card-border); }
    .app-dialog_head h6{ margin: 0; font-weight: 700; color: var(--brand-dark); }
    .app-dialog_x{ border: 0; background: #f1f4fa; width: 32px; height: 32px; border-radius: 50%; line-height: 1; cursor: pointer; }
    .app-dialog_x:hover{ background: #e3e9f5; }
    .app-dialog_body{ padding: 20px 22px 22px; }
    .app-dialog_form{ display: grid; gap: 14px; }
    .app-dialog_confirm{ text-align: center; padding: 26px 24px 22px; }
    .app-dialog_confirm .ic{ display: grid; place-items: center; width: 54px; height: 54px; margin: 0 auto 12px; border-radius: 50%; font-size: 1.5rem; background: #e8f1ff; color: var(--brand-light); }
    .app-dialog_confirm.is-danger .ic{ background: #fdecec; color: #dc3545; }
    .app-dialog_confirm h6{ font-weight: 700; color: var(--brand-dark); margin-bottom: 6px; }
    .app-dialog_confirm p{ color: var(--ink-muted); font-size: .9rem; margin-bottom: 18px; white-space: pre-line; }
    .app-dialog_confirm .actions{ display: flex; justify-content: center; gap: 10px; }
  </style>
  @stack('styles')
</head>
<body>
<div class="app-shell">

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <nav class="sidebar" id="adminSidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand text-decoration-none">
      <div class="logo-badge">AP</div>
      <div class="brand-text">
        <strong>Asa Production</strong>
        <span>Admin Panel</span>
      </div>
    </a>

    <div class="sidebar-scroll">
      <div class="nav-section-label">Utama</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
      </a>
      <a href="{{ route('admin.settings.edit') }}" class="nav-link-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="bi bi-sliders"></i> Pengaturan Situs
      </a>
      <a href="{{ route('admin.translations.index') }}" class="nav-link-item {{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
        <i class="bi bi-translate"></i> Terjemahan (EN)
      </a>

      <div class="nav-section-label">Konten Halaman</div>
      <a href="{{ route('admin.team.index') }}" class="nav-link-item {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Tim Kami
      </a>
      <a href="{{ route('admin.services.index') }}" class="nav-link-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
        <i class="bi bi-award-fill"></i> Keunggulan
      </a>
      <a href="{{ route('admin.portfolio.index') }}" class="nav-link-item {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
        <i class="bi bi-images"></i> Portofolio
      </a>
      <a href="{{ route('admin.posts.index') }}" class="nav-link-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Blog
      </a>
      <a href="{{ route('admin.testimonials.index') }}" class="nav-link-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
        <i class="bi bi-chat-quote-fill"></i> Testimoni
      </a>
      <a href="{{ route('admin.partners.index') }}" class="nav-link-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
        <i class="bi bi-diagram-3-fill"></i> Partner
      </a>
      <a href="{{ route('admin.certificates.index') }}" class="nav-link-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
        <i class="bi bi-patch-check-fill"></i> Sertifikat
      </a>
      <a href="{{ route('admin.faqs.index') }}" class="nav-link-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
        <i class="bi bi-question-circle-fill"></i> FAQ
      </a>
      <a href="{{ route('admin.legal-pages.index') }}" class="nav-link-item {{ request()->routeIs('admin.legal-pages.*') ? 'active' : '' }}">
        <i class="bi bi-shield-check"></i> Halaman Legal
      </a>

      <div class="nav-section-label">Halaman Solusi</div>

      @foreach($solusiGroups as $prefix => $group)
        @php
          $groupActive = request()->routeIs("admin.$prefix.*") || request()->routeIs("admin.$prefix") || request()->routeIs("admin.$prefix-*");
          $collapseId = 'nav-solusi-'.$prefix;
        @endphp
        <a href="#{{ $collapseId }}" class="nav-link-item nav-group_toggle {{ $groupActive ? 'active' : '' }}"
           data-bs-toggle="collapse" role="button"
           aria-expanded="{{ $groupActive ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
          <i class="bi {{ $group['icon'] }}"></i> {{ $group['label'] }}
          <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse nav-group_sub {{ $groupActive ? 'show' : '' }}" id="{{ $collapseId }}">
          <a href="{{ route("admin.$prefix.edit") }}" class="nav-link-item {{ request()->routeIs("admin.$prefix.edit") || request()->routeIs("admin.$prefix.update") ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i> Konten Halaman
          </a>
          <a href="{{ route("admin.$prefix-benefits.index") }}" class="nav-link-item {{ request()->routeIs("admin.$prefix-benefits.*") ? 'active' : '' }}">
            <i class="bi bi-award-fill"></i> Keunggulan
          </a>
          <a href="{{ route("admin.$prefix-clients.index") }}" class="nav-link-item {{ request()->routeIs("admin.$prefix-clients.*") ? 'active' : '' }}">
            <i class="bi bi-diagram-3-fill"></i> Klien
          </a>
          <a href="{{ route("admin.$prefix-portfolios.index") }}" class="nav-link-item {{ request()->routeIs("admin.$prefix-portfolios.*") ? 'active' : '' }}">
            <i class="bi bi-images"></i> Portofolio
          </a>
          <a href="{{ route("admin.$prefix-faqs.index") }}" class="nav-link-item {{ request()->routeIs("admin.$prefix-faqs.*") ? 'active' : '' }}">
            <i class="bi bi-question-circle-fill"></i> FAQ
          </a>
        </div>
      @endforeach

      <div class="nav-section-label">Halaman Produk</div>
      <a href="{{ route('admin.products.index') }}" class="nav-link-item {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.create') ? 'active' : '' }}">
        <i class="bi bi-box-seam-fill"></i> Semua Produk
        <span class="badge-count" style="background:rgba(255,255,255,.14);">{{ $__sidebarProducts->count() }}</span>
      </a>

      @foreach($__sidebarProducts as $__p)
        @php
          $groupActive = (request()->routeIs('admin.products.edit', 'admin.products.update') && optional(request()->route('product'))->id === $__p->id)
            || (request()->routeIs('admin.product-items.*') && optional(request()->route('product'))->id === $__p->id);
          $collapseId = 'nav-produk-'.$__p->id;
          $__routeType = request()->route('type');
        @endphp
        <a href="#{{ $collapseId }}" class="nav-link-item nav-group_toggle {{ $groupActive ? 'active' : '' }}"
           data-bs-toggle="collapse" role="button"
           aria-expanded="{{ $groupActive ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
          <i class="bi bi-box-seam"></i> {{ $__p->name }}
          @unless($__p->is_active)<span class="badge-count" style="background:rgba(255,255,255,.14);">Draft</span>@endunless
          <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse nav-group_sub {{ $groupActive ? 'show' : '' }}" id="{{ $collapseId }}">
          <a href="{{ route('admin.products.edit', $__p) }}" class="nav-link-item {{ request()->routeIs('admin.products.edit', 'admin.products.update') && optional(request()->route('product'))->id === $__p->id ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i> Konten Halaman
          </a>
          <a href="{{ route('admin.product-items.index', [$__p, 'benefits']) }}" class="nav-link-item {{ request()->routeIs('admin.product-items.*') && $__routeType === 'benefits' && optional(request()->route('product'))->id === $__p->id ? 'active' : '' }}">
            <i class="bi bi-award-fill"></i> Keunggulan
          </a>
          <a href="{{ route('admin.product-items.index', [$__p, 'clients']) }}" class="nav-link-item {{ request()->routeIs('admin.product-items.*') && $__routeType === 'clients' && optional(request()->route('product'))->id === $__p->id ? 'active' : '' }}">
            <i class="bi bi-diagram-3-fill"></i> Klien
          </a>
          <a href="{{ route('admin.product-items.index', [$__p, 'faqs']) }}" class="nav-link-item {{ request()->routeIs('admin.product-items.*') && $__routeType === 'faqs' && optional(request()->route('product'))->id === $__p->id ? 'active' : '' }}">
            <i class="bi bi-question-circle-fill"></i> FAQ
          </a>
        </div>
      @endforeach

      <div class="nav-section-label">Pesan Masuk</div>
      <a href="{{ route('admin.contact-messages.index') }}" class="nav-link-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
        <i class="bi bi-envelope-fill"></i> Pesan Kontak
        @php($unreadCount = \App\Models\ContactMessage::unread()->count())
        <span class="badge-count" id="contactUnreadBadge" style="{{ $unreadCount ? '' : 'display:none;' }}">{{ $unreadCount }}</span>
      </a>
    </div>

    {{-- Ingat posisi scroll menu sidebar antar halaman.
         Tiap klik menu = pindah halaman penuh, sehingga scroll sidebar biasanya
         reset ke atas. Skrip ini dijalankan tepat setelah menu dirender (sebelum
         browser menggambar layar) jadi tidak ada efek "loncat ke atas". --}}
    <script>
      (function () {
        var box = document.querySelector('#adminSidebar .sidebar-scroll');
        if (!box) return;
        var KEY = 'adminSidebarScroll';

        function save() {
          try { sessionStorage.setItem(KEY, String(box.scrollTop)); } catch (e) {}
        }

        var saved = null;
        try { saved = sessionStorage.getItem(KEY); } catch (e) {}

        if (saved !== null && !isNaN(parseInt(saved, 10))) {
          box.scrollTop = parseInt(saved, 10);
        }

        // Pastikan menu aktif selalu terlihat (kunjungan pertama, atau redirect
        // ke menu yang posisinya di luar area scroll tersimpan).
        var items = box.querySelectorAll('a.nav-link-item.active');
        var active = items.length ? items[items.length - 1] : null;
        if (active) {
          var b = box.getBoundingClientRect();
          var a = active.getBoundingClientRect();
          if (a.top < b.top + 8) {
            box.scrollTop -= (b.top + 8) - a.top;
          } else if (a.bottom > b.bottom - 8) {
            box.scrollTop += a.bottom - (b.bottom - 8);
          }
        }

        box.addEventListener('click', save);       // klik menu / sub menu
        window.addEventListener('pagehide', save); // submit form, redirect, back/forward
      })();
    </script>

    <div class="sidebar-footer">
      <a href="{{ route('home') }}" target="_blank" class="view-site">
        <i class="bi bi-box-arrow-up-right"></i> Lihat Situs
      </a>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-logout">
          <i class="bi bi-box-arrow-left me-1"></i> Logout
        </button>
      </form>
    </div>
  </nav>

  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle-btn" id="sidebarToggleBtn" type="button" aria-label="Buka menu">
          <i class="bi bi-list fs-5"></i>
        </button>
        <div>
          <h4>@yield('title', 'Dashboard')</h4>
          <div class="breadcrumb-hint">Admin Panel &middot; Asa Production</div>
        </div>
      </div>

      <div class="user-chip">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        <div class="info">
          <strong>{{ auth()->user()->name }}</strong>
          <span>Administrator</span>
        </div>
      </div>
    </div>

    <div class="content-area">
      @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2">
          <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Terjadi kesalahan:</strong>
          </div>
          <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const sidebar = document.getElementById('adminSidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  const toggleBtn = document.getElementById('sidebarToggleBtn');

  function closeSidebar() {
    sidebar.classList.remove('show');
    backdrop.classList.remove('show');
  }

  toggleBtn?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
    backdrop.classList.toggle('show');
  });
  backdrop?.addEventListener('click', closeSidebar);

  // ===== Polling badge "Pesan Kontak" di sidebar (semua halaman admin) =====
  // Tidak memakai websocket/Pusher; cukup cek jumlah pesan belum dibaca
  // secara berkala agar badge terasa real-time tanpa perlu reload manual.
  (function pollContactMessages() {
    const badge = document.getElementById('contactUnreadBadge');
    if (!badge) return;

    const pollUrl = @json(route('admin.contact-messages.poll-status'));

    async function check() {
      try {
        const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const data = await res.json();

        if (data.unread > 0) {
          badge.textContent = data.unread;
          badge.style.display = '';
        } else {
          badge.style.display = 'none';
        }
      } catch (e) {
        // Diamkan saja — koneksi terputus sesaat tidak perlu mengganggu admin.
      }
    }

    setInterval(check, 15000);
  })();
</script>
<script>
  // ===== Popup & tombol modern untuk SEMUA halaman admin =====
  (function () {
    function openDlg(d) { if (d.showModal) { d.showModal(); } else { d.setAttribute('open', ''); } }

    // 1) Buka/tutup popup: data-dialog-open="#id" / data-dialog-close; klik di luar kotak = tutup.
    document.addEventListener('click', function (e) {
      var opener = e.target.closest('[data-dialog-open]');
      if (opener) {
        e.preventDefault();
        var d = document.querySelector(opener.getAttribute('data-dialog-open'));
        if (d) openDlg(d);
        return;
      }
      var closer = e.target.closest('[data-dialog-close]');
      if (closer) { var dd = closer.closest('dialog'); if (dd) dd.close(); return; }
      if (e.target.tagName === 'DIALOG' && e.target.classList.contains('app-dialog')) e.target.close();
    });
    document.querySelectorAll('dialog[data-open-on-load]').forEach(openDlg);

    // 2) Semua tombol/tautan "Tambah ..." (atau menuju /create) otomatis bergaya modern.
    var addRe = /^\s*\+?\s*(tambah|add|buat|new)\b/i;
    document.querySelectorAll('.content-area a.btn, .content-area button.btn').forEach(function (el) {
      if (el.closest('dialog') || el.closest('table') || el.hasAttribute('data-plain')) return;
      if (el.tagName === 'BUTTON' && el.type === 'submit') return; // jangan ubah tombol kirim form
      var label = el.textContent.replace(/\s+/g, ' ').trim();
      var href = el.getAttribute('href') || '';
      if (!(addRe.test(label) || /\/create\/?$/.test(href))) return;
      var small = el.classList.contains('btn-sm');
      el.className = 'btn-add' + (small ? ' btn-add--sm' : '');
      el.innerHTML = '<span class="plus"><i class="bi bi-plus-lg"></i></span><span></span>';
      el.lastChild.textContent = label.replace(/^\+\s*/, '');
    });

    // 3) Popup konfirmasi modern menggantikan window.confirm() bawaan browser.
    var confirmDlg = null;
    function ask(message) {
      return new Promise(function (resolve) {
        if (!confirmDlg) {
          confirmDlg = document.createElement('dialog');
          confirmDlg.className = 'app-dialog';
          confirmDlg.style.width = 'min(400px, calc(100% - 28px))';
          confirmDlg.innerHTML = '<div class="app-dialog_confirm"><div class="ic"><i class="bi"></i></div><h6></h6><p></p>' +
            '<div class="actions"><button type="button" class="btn btn-outline-secondary" data-act="no">Batal</button>' +
            '<button type="button" class="btn" data-act="yes"></button></div></div>';
          document.body.appendChild(confirmDlg);
        }
        var danger = /hapus|delete|remove|buang/i.test(message);
        var box = confirmDlg.firstChild;
        box.classList.toggle('is-danger', danger);
        box.querySelector('.ic i').className = 'bi ' + (danger ? 'bi-trash3-fill' : 'bi-question-circle-fill');
        box.querySelector('h6').textContent = danger ? 'Hapus data ini?' : 'Konfirmasi';
        box.querySelector('p').textContent = message;
        var yes = box.querySelector('[data-act=yes]');
        yes.className = 'btn ' + (danger ? 'btn-danger' : 'btn-dark');
        yes.textContent = danger ? 'Ya, hapus' : 'Ya, lanjutkan';
        var done = function (v) { confirmDlg.removeEventListener('close', onClose); confirmDlg.close(); resolve(v); };
        var onClose = function () { resolve(false); };
        confirmDlg.addEventListener('close', onClose, { once: true });
        box.querySelector('[data-act=no]').onclick = function () { done(false); };
        yes.onclick = function () { done(true); };
        openDlg(confirmDlg);
      });
    }
    window.adminConfirm = ask;

    function pickMessage(el) {
      var attr = el.getAttribute('data-confirm');
      if (attr) return attr;
      var code = el.getAttribute('onsubmit') || el.getAttribute('onclick') || '';
      var m = code.match(/confirm\((['"])([\s\S]*?)\1\s*\)/);
      return m ? m[2].replace(/\\(['"])/g, '$1') : null;
    }

    // Form dengan onsubmit="return confirm('...')" atau data-confirm="..."
    document.querySelectorAll('form[onsubmit*="confirm("], form[data-confirm]').forEach(function (f) {
      var msg = pickMessage(f);
      if (!msg) return;
      f.removeAttribute('onsubmit');
      f.addEventListener('submit', function (e) {
        if (f.__confirmed) { f.__confirmed = false; return; }
        e.preventDefault();
        var submitter = e.submitter;
        ask(msg).then(function (ok) {
          if (!ok) return;
          f.__confirmed = true;
          if (f.requestSubmit) { f.requestSubmit(submitter || undefined); } else { f.submit(); }
        });
      });
    });

    // Tombol dengan onclick="return confirm('...')" atau data-confirm="..." di dalam form
    document.querySelectorAll('button[onclick*="confirm("], button[data-confirm]').forEach(function (b) {
      var msg = pickMessage(b);
      var f = b.form;
      if (!msg || !f) return;
      b.removeAttribute('onclick');
      b.addEventListener('click', function (e) {
        e.preventDefault();
        ask(msg).then(function (ok) {
          if (!ok) return;
          f.__confirmed = true;
          if (f.requestSubmit) { f.requestSubmit(b); } else { f.submit(); }
        });
      });
    });
  })();
</script>
@stack('scripts')
</body>
</html>
