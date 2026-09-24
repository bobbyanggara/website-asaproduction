@extends('layouts.admin')
@section('title', $item->exists ? 'Edit FAQ' : 'Tambah FAQ')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.klinik-asa-faqs.update', $item) : route('admin.klinik-asa-faqs.store') }}">
  @csrf
  @if($item->exists) @method('PUT') @endif

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-12">
        <label class="form-label">Pertanyaan</label>
        <input type="text" name="question" class="form-control" value="{{ old('question', $item->question) }}" required>
      </div>
      <div class="col-12">
        <label class="form-label">Jawaban</label>
        <textarea name="answer" class="form-control" rows="4" required>{{ old('answer', $item->answer) }}</textarea>
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
    <a href="{{ route('admin.klinik-asa-faqs.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection
