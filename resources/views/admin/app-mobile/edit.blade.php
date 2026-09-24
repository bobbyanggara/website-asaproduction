@extends('layouts.admin')
@section('title', 'Jasa Pembuatan Aplikasi Mobile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0">Kelola isi halaman publik <a href="{{ route('solusi.app-mobile') }}" target="_blank" rel="noopener">/solusi/jasa-pembuatan-app-mobile</a>.</p>
  <a href="{{ route('solusi.app-mobile') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Halaman</a>
</div>

<form method="POST" action="{{ route('admin.app-mobile.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">Hero</div>
    <div class="card-body row g-3">
      <div class="col-12">
        <label class="form-label">Judul Hero</label>
        <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $page->hero_title) }}" placeholder="Asa Production, Solusi Tepat untuk Pengembangan Aplikasi Android & iOS yang Mudah dan Efektif">
      </div>
      <div class="col-12">
        <label class="form-label">Subjudul / deskripsi singkat</label>
        <textarea name="hero_subtitle" class="form-control" rows="3">{{ old('hero_subtitle', $page->hero_subtitle) }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Gambar/ilustrasi hero (opsional)</label>
        @if($page->hero_image)<div class="mb-2"><img src="{{ asset('storage/'.$page->hero_image) }}" class="thumb-preview"></div>@endif
        <input type="file" name="hero_image" class="form-control" accept="image/*">
        <div class="form-text">Kalau dikosongkan, hero tampil teks saja (tanpa gambar di sisi kanan).</div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">Preview Solusi</div>
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Judul section</label>
        <input type="text" name="preview_title" class="form-control" value="{{ old('preview_title', $page->preview_title ?? 'Preview Solusi') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Media (gambar, GIF, atau video MP4/WebM)</label>
        @if($page->preview_media)
          <div class="mb-2">
            @if(\Illuminate\Support\Str::endsWith(strtolower($page->preview_media), ['.mp4', '.webm']))
              <video src="{{ asset('storage/'.$page->preview_media) }}" class="thumb-preview" controls muted></video>
            @else
              <img src="{{ asset('storage/'.$page->preview_media) }}" class="thumb-preview">
            @endif
          </div>
        @endif
        <input type="file" name="preview_media" class="form-control" accept="image/*,video/mp4,video/webm">
        <div class="form-text">Boleh foto/JPG/PNG, GIF animasi, atau video MP4/WebM pendek — semuanya otomatis ditampilkan dengan cara yang sesuai di halaman publik.</div>
      </div>
      <div class="col-12">
        <label class="form-label">Keterangan singkat di bawah media (opsional)</label>
        <textarea name="preview_caption" class="form-control" rows="2">{{ old('preview_caption', $page->preview_caption) }}</textarea>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-3">
    <div class="card-header fw-semibold">SEO</div>
    <div class="card-body row g-3">
      <div class="col-12">
        <label class="form-label">Meta description</label>
        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description) }}" maxlength="255">
      </div>
    </div>
  </div>

  <div class="alert alert-info">
    Poin "Keunggulan" dikelola terpisah di menu <a href="{{ route('admin.app-mobile-benefits.index') }}">Keunggulan App Mobile</a>. Portofolio yang tampil di halaman ini otomatis diambil dari menu Portofolio dengan kategori <strong>"Jasa Pembuatan Aplikasi"</strong>. Logo klien dikelola sendiri di menu <a href="{{ route('admin.app-mobile-clients.index') }}">Klien App Mobile</a> (tidak diambil dari menu Partner di beranda).
  </div>

  @include('admin.partials.en-fields', ['item' => $page])

  <div class="mt-3">
    <button type="submit" class="btn btn-dark">Simpan</button>
  </div>
</form>
@endsection
