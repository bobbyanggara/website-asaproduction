@extends('layouts.admin')
@section('title', $legalApp ? 'Legal: '.$legalApp->name : 'Legal: Situs Asa Production')

@section('content')
<nav class="mb-3 small">
  <a href="{{ route('admin.legal-pages.index') }}"><i class="bi bi-arrow-left"></i> Pilih Aplikasi</a>
  <span class="text-muted mx-1">/</span>
  <strong>{{ $legalApp ? $legalApp->name : 'Situs Asa Production (umum)' }}</strong>
</nav>

@if($legalApp)
  @if($errors->appSettings->any())
    <div class="alert alert-danger py-2"><ul class="mb-0 small">@foreach($errors->appSettings->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('admin.legal-pages.apps.settings', $legalApp) }}" class="card shadow-sm mb-3">
    @csrf
    @method('PUT')
    <div class="card-header bg-white fw-semibold"><i class="bi bi-gear-fill me-1"></i> Pengaturan Aplikasi</div>
    <div class="card-body row g-3">
      <div class="col-md-4">
        <label class="form-label">Nama Aplikasi *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $legalApp->name) }}" maxlength="120" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Alamat (slug) *</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $legalApp->slug) }}" maxlength="80" required>
      </div>
      <div class="col-md-5">
        <label class="form-label">Deskripsi singkat</label>
        <input type="text" name="description" class="form-control" value="{{ old('description', $legalApp->description) }}" maxlength="300">
      </div>
      <div class="col-12 d-flex flex-wrap align-items-center gap-3">
        <div class="form-check">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="app_active" {{ old('is_active', $legalApp->is_active) ? 'checked' : '' }}>
          <label class="form-check-label" for="app_active">Aktif (dokumen bisa dibuka publik)</label>
        </div>
        <button type="submit" class="btn btn-dark btn-sm">Simpan Pengaturan</button>
        <span class="small text-muted"><i class="bi bi-exclamation-triangle"></i> Mengubah slug mengubah alamat publik. Perbarui juga tautan di Play Store / App Store.</span>
      </div>
    </div>
  </form>
@endif

<div class="card shadow-sm">
  <div class="card-header bg-white fw-semibold"><i class="bi bi-files me-1"></i> Dokumen {{ $legalApp ? $legalApp->name : 'Situs' }} <span class="text-muted fw-normal small">&mdash; langkah 2: pilih dokumen untuk diedit</span></div>
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead>
        <tr>
          <th class="ps-3">Dokumen</th>
          <th>Alamat Publik</th>
          <th>Berlaku / Diperbarui</th>
          <th>Status</th>
          <th class="text-end pe-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
        @php $editUrl = $legalApp ? route('admin.legal-pages.apps.edit', [$legalApp, $item->slug]) : route('admin.legal-pages.edit', $item->slug); @endphp
        <tr>
          <td class="ps-3">
            <i class="bi {{ $item->slug === 'terms' ? 'bi-file-earmark-text-fill' : 'bi-shield-lock-fill' }} me-1 text-primary"></i>
            <strong>{{ $item->slug === 'terms' ? 'Ketentuan Layanan' : 'Kebijakan Privasi' }}</strong>
          </td>
          <td>
            <input type="text" class="form-control form-control-sm" style="min-width:260px" value="{{ $item->publicUrl() }}" readonly onfocus="this.select()">
          </td>
          <td>{{ $item->effective_date ? $item->effective_date->translatedFormat('j F Y') : '-' }}</td>
          <td>@if($item->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
          <td class="text-end pe-3 text-nowrap">
            <a href="{{ $editUrl }}" class="btn btn-sm btn-dark">Edit</a>
            <a href="{{ $item->publicUrl() }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@if($legalApp)
  <form method="POST" action="{{ route('admin.legal-pages.apps.destroy', $legalApp) }}" class="mt-4"
        onsubmit="return confirm('Hapus aplikasi {{ addslashes($legalApp->name) }} beserta Ketentuan Layanan & Kebijakan Privasinya? Tindakan ini tidak bisa dibatalkan.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Hapus Aplikasi Ini</button>
  </form>
@endif
@endsection
