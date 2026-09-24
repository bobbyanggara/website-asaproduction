@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Testimoni' : 'Tambah Testimoni')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.testimonials.update', $item) : route('admin.testimonials.store') }}" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Perusahaan</label>
        <input type="text" name="company" class="form-control" value="{{ old('company', $item->company) }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Jabatan (opsional)</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
      </div>
      <div class="col-12">
        <label class="form-label">Pesan Testimoni</label>
        <textarea name="message" class="form-control" rows="3" required>{{ old('message', $item->message) }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Foto</label>
        @if($item->photo)<div class="mb-2"><img src="{{ asset('storage/'.$item->photo) }}" class="thumb-preview"></div>@endif
        <input type="file" name="photo" class="form-control" accept="image/*">
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
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection
