@extends('layouts.admin')
@section('title', 'Edit '.$item->title.(!empty($legalApp) ? ' - '.$legalApp->name : ''))

@push('styles')
<style>
  .legal-editor-wrap{ position: relative; display: flow-root; background: #fff; border-radius: 10px; }
  .legal-editor-wrap .ql-toolbar.ql-snow{ border-color: var(--card-border); border-radius: 10px 10px 0 0; }
  .legal-editor-wrap .ql-container.ql-snow{ height: 460px; border-color: var(--card-border); border-radius: 0 0 10px 10px; font-family: inherit; font-size: .95rem; }
  .legal-editor-wrap .ql-editor{ height: 100%; overflow-y: auto; overflow-wrap: anywhere; }
  .token-chip{ font-family: monospace; background: #eef3fb; border: 1px solid var(--card-border); border-radius: 6px; padding: 1px 6px; font-size: .8rem; }
</style>
@endpush

@section('content')
@php
  $legalApp = $legalApp ?? null;
  $backUrl = $legalApp ? route('admin.legal-pages.apps.show', $legalApp) : route('admin.legal-pages.general');
  $formUrl = $legalApp ? route('admin.legal-pages.apps.update', [$legalApp, $item->slug]) : route('admin.legal-pages.update', $item->slug);
@endphp
<nav class="mb-3 small">
  <a href="{{ route('admin.legal-pages.index') }}">Pilih Aplikasi</a>
  <span class="text-muted mx-1">/</span>
  <a href="{{ $backUrl }}">{{ $legalApp ? $legalApp->name : 'Situs Asa Production (umum)' }}</a>
  <span class="text-muted mx-1">/</span>
  <strong>{{ $item->slug === 'terms' ? 'Ketentuan Layanan' : 'Kebijakan Privasi' }}</strong>
</nav>
<form method="POST" id="legalPageForm" action="{{ $formUrl }}">
  @csrf
  @method('PUT')

  <div class="card shadow-sm">
    <div class="card-body row g-3">
      <div class="col-md-6">
        <label class="form-label">Judul *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Berlaku sejak / diperbarui</label>
        <input type="date" name="effective_date" class="form-control" value="{{ old('effective_date', optional($item->effective_date)->format('Y-m-d')) }}">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
          <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Aktif (tampil di situs)</label>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">Ringkasan (tampil di bawah judul)</label>
        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $item->excerpt) }}</textarea>
      </div>

      <div class="col-12">
        <label class="form-label">Isi Halaman (Bahasa Indonesia) *</label>
        <div class="legal-editor-wrap"><div id="contentEditor"></div></div>
        <textarea name="content" id="contentInput" style="display:none">{{ old('content', $item->content) }}</textarea>
        <div class="form-text mt-2">
          Gunakan <strong>Heading H2</strong> untuk tiap pasal/bagian (mis. &ldquo;1. Penerimaan Ketentuan&rdquo;) &mdash;
          heading H2 otomatis menjadi <strong>Daftar Isi</strong> di halaman publik.
          Token berikut diganti otomatis dari Pengaturan Situs:
          <span class="token-chip">{app}</span>
          <span class="token-chip">{site}</span>
          <span class="token-chip">{email}</span>
          <span class="token-chip">{address}</span>
          <span class="token-chip">{phone}</span>
          <span class="token-chip">{website}</span>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">Meta Description (SEO, opsional)</label>
        <textarea name="meta_description" id="meta_description" class="form-control" rows="2" maxlength="160">{{ old('meta_description', $item->meta_description) }}</textarea>
        <div class="form-text"><span id="meta_count">{{ strlen(old('meta_description', $item->meta_description ?? '')) }}</span>/160 karakter. Kosongkan untuk memakai ringkasan.</div>
      </div>
    </div>
  </div>

  {{-- Versi English: judul & ringkasan lewat partial bawaan, isi lewat editor sendiri (HTML panjang). --}}
  @include('admin.partials.en-fields', ['item' => $item, 'skip' => ['content']])

  <details class="card shadow-sm mt-3">
    <summary class="card-header bg-white fw-semibold" style="cursor:pointer;">
      <i class="bi bi-translate me-1"></i> Isi Halaman (English)
      <span class="text-muted fw-normal small">&mdash; otomatis diterjemahkan saat disimpan jika dikosongkan</span>
    </summary>
    <div class="card-body">
      <div class="legal-editor-wrap"><div id="contentEditorEn"></div></div>
      <textarea name="content_en" id="contentInputEn" style="display:none">{{ old('content_en', $item->content_en) }}</textarea>
      <div class="form-text mt-2">
        Cukup isi bahasa Indonesia di atas. Versi Inggris dibuat otomatis saat <strong>Simpan</strong>; edit di sini hanya untuk memperbaiki hasilnya.
        Kalau Anda mengubah isi Indonesia dan membiarkan kolom ini apa adanya, terjemahan akan dibuat ulang.
      </div>
    </div>
  </details>

  <div class="mt-3 d-flex gap-2">
    <button type="submit" class="btn btn-dark">Simpan</button>
    <a href="{{ $backUrl }}" class="btn btn-outline-secondary">Kembali</a>
    <a href="{{ $item->publicUrl() }}" target="_blank" class="btn btn-outline-secondary ms-auto"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat halaman</a>
  </div>
</form>

<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
  const metaEl = document.getElementById('meta_description');
  const counterEl = document.getElementById('meta_count');
  metaEl.addEventListener('input', () => counterEl.textContent = metaEl.value.length);

  (function () {
    if (typeof Quill === 'undefined') return;

    const looksLikeHtml = (s) => /<(p|h[1-6]|ul|ol|li|blockquote|img|figure|table|pre)[\s>]/i.test(s);
    const escapeHtml = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    function makeEditor(editorSel, inputId, placeholder) {
      const input = document.getElementById(inputId);
      const quill = new Quill(editorSel, {
        theme: 'snow',
        placeholder: placeholder,
        modules: {
          toolbar: [
            [{ header: [2, 3, 4, false] }],
            ['bold', 'italic', 'underline'],
            ['blockquote'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean'],
          ],
        },
      });

      const raw = input.value || '';
      const html = looksLikeHtml(raw)
        ? raw
        : raw.split(/\n{2,}/).map((p) => p.trim()).filter(Boolean)
            .map((p) => '<p>' + escapeHtml(p).replace(/\n/g, '<br>') + '</p>').join('');
      if (html) quill.clipboard.dangerouslyPasteHTML(html);

      return { quill, input };
    }

    const id = makeEditor('#contentEditor', 'contentInput', 'Tulis isi halaman di sini…');
    const en = makeEditor('#contentEditorEn', 'contentInputEn', 'English version (otomatis jika dikosongkan)…');

    // PENTING: jangan pakai document.querySelector('form') — form pertama di halaman
    // admin adalah form Logout di sidebar, sehingga isi editor tidak pernah disalin
    // ke textarea dan yang tersimpan selalu isi lama.
    document.getElementById('legalPageForm').addEventListener('submit', function () {
      id.input.value = id.quill.root.innerHTML;
      // Editor kosong menghasilkan <p><br></p>; kirim string kosong supaya diterjemahkan otomatis.
      en.input.value = en.quill.getText().trim() === '' ? '' : en.quill.root.innerHTML;
    });
  })();
</script>
@endsection
