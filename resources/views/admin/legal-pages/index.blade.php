@extends('layouts.admin')
@section('title', 'Halaman Legal')

@push('styles')
<style>
  .legal-head{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:14px; margin-bottom:16px; }
  .legal-head h5{ margin:0; font-weight:800; color:var(--brand-dark); }
  .legal-head p{ margin:2px 0 0; color:#6b7385; font-size:.875rem; }
</style>
@endpush

@section('content')
<div class="legal-head">
  <div>
    <h5>Pilih Aplikasi</h5>
    <p>Klik kartu untuk mengelola dokumen legalnya.</p>
  </div>
  <x-admin.add-button target="addAppModal">Tambah Aplikasi</x-admin.add-button>
</div>

<div class="alert alert-info d-flex gap-2 align-items-start">
  <i class="bi bi-info-circle-fill mt-1"></i>
  <div>
    <strong>Langkah 1 &mdash; pilih aplikasi.</strong> Setiap aplikasi punya <strong>Ketentuan Layanan</strong> dan
    <strong>Kebijakan Privasi</strong> sendiri dengan alamat publik sendiri. Isi bawaan adalah template umum &mdash;
    sesuaikan dengan praktik data aplikasi Anda dan sebaiknya ditinjau penasihat hukum sebelum dipublikasikan.
  </div>
</div>

<div class="row g-3">
  {{-- Halaman umum situs --}}
  <div class="col-md-6 col-xl-4">
    <a href="{{ route('admin.legal-pages.general') }}" class="card shadow-sm h-100 text-decoration-none text-reset">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <i class="bi bi-globe2 fs-3 text-primary"></i>
          <span class="badge bg-dark">Umum</span>
        </div>
        <h6 class="fw-bold mb-1">Situs Asa Production</h6>
        <p class="small text-muted mb-2">Ketentuan Layanan &amp; Kebijakan Privasi untuk website utama (tautan di footer).</p>
        <span class="small text-primary">Kelola dokumen <i class="bi bi-arrow-right"></i></span>
      </div>
    </a>
  </div>

  {{-- Daftar aplikasi --}}
  @foreach($apps as $app)
  <div class="col-md-6 col-xl-4">
    <a href="{{ route('admin.legal-pages.apps.show', $app) }}" class="card shadow-sm h-100 text-decoration-none text-reset">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <i class="bi bi-phone-fill fs-3 text-primary"></i>
          @if($app->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif
        </div>
        <h6 class="fw-bold mb-1">{{ $app->name }}</h6>
        <p class="small text-muted mb-1">{{ $app->description ?: 'Belum ada deskripsi.' }}</p>
        <code class="small d-block mb-2">/{{ $app->slug }}/&hellip;</code>
        <span class="small text-primary">Kelola dokumen <i class="bi bi-arrow-right"></i></span>
      </div>
    </a>
  </div>
  @endforeach
</div>

{{-- Tambah aplikasi: popup seragam admin (lihat components/admin/modal.blade.php) --}}
<x-admin.modal id="addAppModal" title="Tambah Aplikasi Baru" :open="$errors->newApp->any()">
  <form method="POST" action="{{ route('admin.legal-pages.apps.store') }}" class="app-dialog_form">
    @csrf
    @if($errors->newApp->any())
      <div class="alert alert-danger mb-0 py-2">
        <ul class="mb-0 small">@foreach($errors->newApp->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif
    <div>
      <label class="form-label">Nama Aplikasi *</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Kalkulator Shopee Seller" maxlength="120" required>
    </div>
    <div>
      <label class="form-label">Alamat (slug)</label>
      <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="otomatis dari nama" maxlength="80">
      <div class="form-text">Menjadi alamat publik: <code>/<span id="slugPreview">nama-aplikasi</span>/kebijakan-privasi</code></div>
    </div>
    <div>
      <label class="form-label">Deskripsi singkat</label>
      <input type="text" name="description" class="form-control" value="{{ old('description') }}" maxlength="300">
    </div>
    <div class="d-flex justify-content-end gap-2 pt-1">
      <button type="button" class="btn btn-outline-secondary" data-dialog-close>Batal</button>
      <button type="submit" class="btn btn-dark px-4">Tambah Aplikasi</button>
    </div>
    <div class="small text-muted">Ketentuan Layanan &amp; Kebijakan Privasi dibuat otomatis dari template, lalu bisa Anda edit.</div>
  </form>
</x-admin.modal>

@push('scripts')
<script>
  // Pratinjau alamat publik saat mengetik nama/slug.
  (function () {
    var dlg = document.getElementById('addAppModal');
    var name = dlg.querySelector('[name=name]'), slug = dlg.querySelector('[name=slug]'), out = document.getElementById('slugPreview');
    function slugify(t) { return t.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, ''); }
    function preview() { out.textContent = slugify(slug.value || name.value) || 'nama-aplikasi'; }
    name.addEventListener('input', preview); slug.addEventListener('input', preview); preview();
  })();
</script>
@endpush
@endsection
