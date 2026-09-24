@extends('layouts.admin')
@section('title', ($item->exists ? 'Edit Klien - ' : 'Tambah Klien - ').$product->name)

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.product-items.update', [$product, 'clients', $item->id]) : route('admin.product-items.store', [$product, 'clients']) }}" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Nama (opsional)</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Logo {{ $item->exists ? '' : '*' }}</label>
        @if($item->logo)<div class="mb-2"><img src="{{ asset('storage/'.$item->logo) }}" class="thumb-preview"></div>@endif
        <input type="file" name="logo" class="form-control" accept="image/*" {{ $item->exists ? '' : 'required' }}>
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

  <div class="mt-3">
    <button type="submit" class="btn btn-dark">Simpan</button>
    <a href="{{ route('admin.product-items.index', [$product, 'clients']) }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection
