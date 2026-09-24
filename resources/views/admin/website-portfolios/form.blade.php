@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Portofolio - Jasa Website' : 'Tambah Portofolio - Jasa Website')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.website-portfolios.update', $item) : route('admin.website-portfolios.store') }}" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  @unless($item->exists)
    @include('admin.partials.portfolio-picker')
    <details class="mb-3" {{ $errors->any() && !old('portfolio_ids') ? 'open' : '' }}>
      <summary class="text-muted mb-2" style="cursor:pointer;">Atau upload gambar baru secara manual</summary>
  @endunless

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-md-8">
        <label class="form-label">Judul (opsional)</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Link proyek (opsional, mis. link demo/live)</label>
        <input type="url" name="link" class="form-control" value="{{ old('link', $item->link) }}" placeholder="https://...">
      </div>
      <div class="col-md-6">
        <label class="form-label">Thumbnail (tampil di grid "Temukan inspirasimu") </label>
        @if($item->thumbnail)<div class="mb-2"><img src="{{ asset('storage/'.$item->thumbnail) }}" class="thumb-preview"></div>@endif
        <input type="file" name="thumbnail" class="form-control" accept="image/*" >
      </div>
      <div class="col-md-6">
        <label class="form-label">Gambar Full-size (opsional — default pakai thumbnail)</label>
        @if($item->image)<div class="mb-2"><img src="{{ asset('storage/'.$item->image) }}" class="thumb-preview"></div>@endif
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <div class="col-md-8">
        <label class="form-label">Deskripsi singkat (opsional)</label>
        <textarea name="description" class="form-control" rows="2" maxlength="500">{{ old('description', $item->description) }}</textarea>
      </div>
      <div class="col-md-2">
        <label class="form-label">Urutan</label>
        <input type="number" name="order_index" class="form-control" value="{{ old('order_index', $item->order_index ?? 0) }}">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <div class="form-check">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Aktif (tampil di situs)</label>
        </div>
      </div>
    </div>
  </div>

  @unless($item->exists)
    </details>
  @endunless

  @include('admin.partials.en-fields', ['item' => $item])

  <div class="mt-3">
    <button type="submit" class="btn btn-dark">Simpan</button>
    <a href="{{ route('admin.website-portfolios.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection
