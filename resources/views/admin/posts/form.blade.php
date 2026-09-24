@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Artikel' : 'Tulis Artikel')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.posts.update', $item) : route('admin.posts.store') }}" enctype="multipart/form-data">
  @csrf
  @if($item->exists) @method('PUT') @endif

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Judul *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Penulis</label>
        <input type="text" name="author" class="form-control" value="{{ old('author', $item->author ?? 'Admin') }}" placeholder="Admin">
      </div>
      <div class="col-md-3">
        <label class="form-label">Slug (opsional, otomatis dari judul)</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}" placeholder="contoh-judul-artikel">
      </div>

      <div class="col-12">
        <label class="form-label">Ringkasan (excerpt, tampil di daftar blog)</label>
        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $item->excerpt) }}</textarea>
      </div>

      <div class="col-12">
        <label class="form-label">Isi Artikel *</label>
        <textarea name="content" class="form-control" rows="12" required>{{ old('content', $item->content) }}</textarea>
        <div class="form-text">Setiap baris baru otomatis jadi paragraf baru di halaman blog.</div>
      </div>

      <div class="col-12">
        <label class="form-label">
          Meta Description * <span class="text-danger">(wajib diisi — dipakai untuk SEO / tag &lt;meta name="description"&gt;)</span>
        </label>
        <textarea name="meta_description" id="meta_description" class="form-control" rows="2" maxlength="160" required>{{ old('meta_description', $item->meta_description) }}</textarea>
        <div class="form-text"><span id="meta_count">{{ strlen(old('meta_description', $item->meta_description ?? '')) }}</span>/160 karakter. Idealnya 120–160 karakter agar tampil optimal di hasil pencarian Google.</div>
      </div>

      <div class="col-md-6">
        <label class="form-label">Thumbnail {{ $item->exists ? '(opsional, kosongkan jika tidak ganti)' : '(opsional)' }}</label>
        @if($item->thumbnail)<div class="mb-2"><img src="{{ asset('storage/'.$item->thumbnail) }}" class="thumb-preview"></div>@endif
        <input type="file" name="thumbnail" class="form-control" accept="image/*">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal Publish (opsional)</label>
        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($item->published_at)->format('Y-m-d\TH:i')) }}">
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
    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>

<script>
  const metaEl = document.getElementById('meta_description');
  const counterEl = document.getElementById('meta_count');
  metaEl.addEventListener('input', () => counterEl.textContent = metaEl.value.length);
</script>
@endsection
