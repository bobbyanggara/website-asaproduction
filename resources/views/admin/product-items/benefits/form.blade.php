@extends('layouts.admin')
@section('title', ($item->exists ? 'Edit Keunggulan - ' : 'Tambah Keunggulan - ').$product->name)

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.product-items.update', [$product, 'benefits', $item->id]) : route('admin.product-items.store', [$product, 'benefits']) }}" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Ikon (kelas CSS font-awesome, opsional)</label>
        <input type="text" name="icon" class="form-control" value="{{ old('icon', str_contains((string) $item->icon, '/') ? '' : $item->icon) }}" placeholder="fa-users">
        <div class="form-text">Lihat daftar ikon di <a href="https://fontawesome.com/v4/icons/" target="_blank" rel="noopener">fontawesome.com/v4/icons</a>, salin nama kelasnya (mis. fa-users).</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Atau upload gambar/icon sendiri</label>
        @if($item->icon && str_contains($item->icon, '/'))<div class="mb-2"><img src="{{ asset('storage/'.$item->icon) }}" class="thumb-preview"></div>@endif
        <input type="file" name="icon_file" class="form-control" accept="image/*">
        <div class="form-text">Kalau upload gambar di sini, field kelas font-awesome di atas diabaikan (gambar yang dipakai).</div>
      </div>
      <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
      </div>
      <div class="col-md-3">
        <label class="form-label">Urutan</label>
        <input type="number" name="order_index" class="form-control" value="{{ old('order_index', $item->order_index ?? 0) }}">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Aktif (tampil di situs)</label>
        </div>
      </div>
    </div>
  </div>

  @include('admin.partials.en-fields', ['item' => $item])

  <div class="mt-3">
    <button type="submit" class="btn btn-dark">Simpan</button>
    <a href="{{ route('admin.product-items.index', [$product, 'benefits']) }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection
