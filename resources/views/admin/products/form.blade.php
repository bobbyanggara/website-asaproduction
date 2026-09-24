@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Produk - '.$item->name : 'Tambah Produk')

@section('content')
@if($item->exists)
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0">Kelola isi halaman publik
    @if($item->is_active)
      <a href="{{ route('produk.show', $item->slug) }}" target="_blank" rel="noopener">/produk/{{ $item->slug }}</a>.
    @else
      <span class="badge bg-secondary">Nonaktif</span> halaman ini belum bisa dibuka publik dan tidak tampil di navbar.
    @endif
  </p>
  @if($item->is_active)
  <a href="{{ route('produk.show', $item->slug) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Halaman</a>
  @endif
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
  <strong>Belum bisa disimpan:</strong>
  <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ $item->exists ? route('admin.products.update', $item) : route('admin.products.store') }}" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  {{-- ===== Identitas & navbar ===== --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">Identitas Produk &amp; Tampilan di Navbar</div>
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Nama produk *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required maxlength="120" placeholder="Kasir ASA">
      </div>
      <div class="col-md-6">
        <label class="form-label">Slug (alamat halaman)</label>
        <div class="input-group">
          <span class="input-group-text">/produk/</span>
          <input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}" maxlength="120" placeholder="otomatis dari nama">
        </div>
        <div class="form-text">
          @if($item->exists)
            Kosongkan = slug tidak berubah. <strong class="text-danger">Hati-hati:</strong> mengubah slug membuat link lama ke halaman ini (mis. yang sudah dibagikan/di Google) tidak lagi berfungsi.
          @else
            Kosongkan untuk dibuat otomatis dari nama.
          @endif
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Ikon navbar (font-awesome)</label>
        <input type="text" name="nav_icon" class="form-control" value="{{ old('nav_icon', $item->nav_icon) }}" placeholder="fa-cube">
        <div class="form-text">Salin nama kelasnya dari <a href="https://fontawesome.com/v4/icons/" target="_blank" rel="noopener">fontawesome.com/v4/icons</a> (mis. <code>fa-shopping-cart</code>). Kosong = <code>fa-cube</code>.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Deskripsi singkat di navbar</label>
        <input type="text" name="nav_description" class="form-control" value="{{ old('nav_description', $item->nav_description) }}" maxlength="255" placeholder="Aplikasi kasir & stok untuk toko Anda">
        <div class="form-text">Teks kecil di bawah nama produk pada dropdown "Produk".</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Deskripsi navbar (English, opsional)</label>
        <input type="text" name="nav_description_en" class="form-control" value="{{ old('nav_description_en', $item->nav_description_en) }}" maxlength="255" placeholder="Point-of-sale & inventory app for your store">
        <div class="form-text">Dipakai saat pengunjung memilih bahasa English. Kosong = memakai teks Indonesia.</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Urutan di navbar</label>
        <input type="number" name="order_index" class="form-control" value="{{ old('order_index', $item->order_index ?? 0) }}">
        <div class="form-text">Angka kecil tampil lebih dulu.</div>
      </div>
      <div class="col-md-3 d-flex align-items-start pt-4">
        <div class="form-check">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Aktif (tampil di situs)</label>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== Hero ===== --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">Hero</div>
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Teks kecil di atas judul (opsional)</label>
        <input type="text" name="eyebrow" class="form-control" value="{{ old('eyebrow', $item->eyebrow) }}" maxlength="120" placeholder="Produk Kasir ASA">
        <div class="form-text">Kosong = otomatis "Produk {nama}".</div>
      </div>
      <div class="col-12">
        <label class="form-label">Judul Hero</label>
        <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $item->hero_title) }}" placeholder="Kasir ASA, Aplikasi Kasir yang Cepat dan Mudah">
        <div class="form-text">Kosong = memakai nama produk.</div>
      </div>
      <div class="col-12">
        <label class="form-label">Subjudul / deskripsi singkat</label>
        <textarea name="hero_subtitle" class="form-control" rows="3">{{ old('hero_subtitle', $item->hero_subtitle) }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Gambar/ilustrasi hero (opsional)</label>
        @if($item->hero_image)<div class="mb-2"><img src="{{ asset('storage/'.$item->hero_image) }}" class="thumb-preview" alt=""></div>@endif
        <input type="file" name="hero_image" class="form-control" accept="image/*">
        <div class="form-text">Kalau dikosongkan, hero tampil teks saja (tanpa gambar di sisi kanan).</div>
      </div>
    </div>
  </div>

  {{-- ===== Preview ===== --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">Preview Produk</div>
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Judul section</label>
        <input type="text" name="preview_title" class="form-control" value="{{ old('preview_title', $item->preview_title) }}" placeholder="Preview {{ $item->name ?: 'nama produk' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Media (gambar, GIF, atau video MP4/WebM)</label>
        @if($item->preview_media)
          <div class="mb-2">
            @if(\Illuminate\Support\Str::endsWith(strtolower($item->preview_media), ['.mp4', '.webm']))
              <video src="{{ asset('storage/'.$item->preview_media) }}" class="thumb-preview" controls muted></video>
            @else
              <img src="{{ asset('storage/'.$item->preview_media) }}" class="thumb-preview" alt="">
            @endif
          </div>
        @endif
        <input type="file" name="preview_media" class="form-control" accept="image/*,video/mp4,video/webm">
        <div class="form-text">Section preview hanya tampil kalau media diisi.</div>
      </div>
      <div class="col-12">
        <label class="form-label">Keterangan singkat di bawah media (opsional)</label>
        <textarea name="preview_caption" class="form-control" rows="2">{{ old('preview_caption', $item->preview_caption) }}</textarea>
      </div>
    </div>
  </div>

  {{-- ===== SEO ===== --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">SEO</div>
    <div class="card-body row g-3">
      <div class="col-12">
        <label class="form-label">Meta description</label>
        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $item->meta_description) }}" maxlength="255">
      </div>
    </div>
  </div>

  @if($item->exists)
  <div class="alert alert-info">
    Poin <strong>Keunggulan</strong> ({{ $item->benefits_count }}), logo <strong>Klien</strong> ({{ $item->clients_count }}), dan <strong>FAQ</strong> ({{ $item->faqs_count }}) dikelola terpisah:
    <a href="{{ route('admin.product-items.index', [$item, 'benefits']) }}">Keunggulan</a> ·
    <a href="{{ route('admin.product-items.index', [$item, 'clients']) }}">Klien</a> ·
    <a href="{{ route('admin.product-items.index', [$item, 'faqs']) }}">FAQ</a>.
  </div>
  @else
  <div class="alert alert-info">Setelah produk disimpan, kamu bisa mengisi <strong>Keunggulan</strong>, logo <strong>Klien</strong>, dan <strong>FAQ</strong>-nya.</div>
  @endif

  @include('admin.partials.en-fields', ['item' => $item, 'skip' => ['nav_description']])

  <div class="mt-3">
    <button type="submit" class="btn btn-dark">Simpan</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">{{ $item->exists ? 'Kembali' : 'Batal' }}</a>
  </div>
</form>
@endsection
