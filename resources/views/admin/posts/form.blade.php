@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Artikel' : 'Tulis Artikel')

@push('styles')
<style>
  /* Perbaikan teks bertumpuk: tinggi editor Quill harus diatur di .ql-container
     (bukan di elemen #contentEditor), dan isi yang panjang di-scroll DI DALAM
     editor. Tanpa ini, isi meluap keluar kotak dan menimpa teks bantuan +
     counter meta description di bawahnya. */
  .post-editor-wrap {
    position: relative;
    display: flow-root;      /* wadah menghitung tinggi anak-anaknya, tidak meluap */
    background: #fff;
    border-radius: 10px;
  }
  .post-editor-wrap .ql-toolbar.ql-snow {
    border-color: var(--card-border);
    border-radius: 10px 10px 0 0;
  }
  .post-editor-wrap .ql-container.ql-snow {
    height: 340px;           /* tinggi tetap; isi lebih panjang di-scroll */
    border-color: var(--card-border);
    border-radius: 0 0 10px 10px;
    font-family: inherit;
    font-size: .95rem;
  }
  .post-editor-wrap .ql-editor {
    height: 100%;
    overflow-y: auto;
    overflow-wrap: anywhere;
  }
  .post-editor-wrap .ql-editor img { max-width: 100%; height: auto; }
</style>
@endpush

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
        <div class="post-editor-wrap">
          <div id="contentEditor"></div>
        </div>
        <textarea name="content" id="contentInput" style="display:none" required>{{ old('content', $item->content) }}</textarea>
        <div class="form-text mt-2">
          Pakai heading (H2/H3), daftar, kutipan, dan gambar seperlunya — artikel dengan struktur
          jelas lebih enak dibaca dan lebih baik untuk SEO daripada satu blok teks panjang.
          Untuk gambar, tombol <strong>gambar</strong> di toolbar akan minta URL gambar (upload dulu
          gambarnya ke tempat lain, lalu tempel link-nya di sini).
        </div>
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

{{-- Editor artikel rich text (Quill) — supaya admin bisa bikin artikel dengan
     heading, daftar, kutipan, dan gambar, bukan cuma paragraf teks polos.
     Isi tersimpan sebagai HTML di textarea #contentInput, lalu dibersihkan lagi
     di server (lihat App\Support\HtmlSanitizer) sebelum disimpan ke database. --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
  const metaEl = document.getElementById('meta_description');
  const counterEl = document.getElementById('meta_count');
  metaEl.addEventListener('input', () => counterEl.textContent = metaEl.value.length);

  (function () {
    const input = document.getElementById('contentInput');
    const editorEl = document.getElementById('contentEditor');
    if (!input || !editorEl || typeof Quill === 'undefined') return;

    const quill = new Quill('#contentEditor', {
      theme: 'snow',
      placeholder: 'Tulis isi artikel di sini…',
      modules: {
        toolbar: [
          [{ header: [2, 3, 4, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          ['blockquote', 'code-block'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link', 'image'],
          ['clean'],
        ],
      },
    });

    // Isi awal: kalau field lama masih teks polos (belum pernah dipakai rich
    // editor), pecah per baris kosong jadi <p> dulu supaya paragraf tidak
    // hilang saat dipindah ke editor.
    const raw = input.value || '';
    const looksLikeHtml = /<(p|h[1-6]|ul|ol|li|blockquote|img|figure|table|pre)[\s>]/i.test(raw);
    const escapeHtml = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const initialHtml = looksLikeHtml
      ? raw
      : raw.split(/\n{2,}/).map((p) => p.trim()).filter(Boolean)
          .map((p) => '<p>' + escapeHtml(p).replace(/\n/g, '<br>') + '</p>').join('');

    if (initialHtml) {
      quill.clipboard.dangerouslyPasteHTML(initialHtml);
    }

    // Tombol gambar: minta URL (bukan upload langsung), supaya artikel tidak
    // membengkak berisi gambar base64 di database.
    quill.getModule('toolbar').addHandler('image', function () {
      const url = window.prompt('Masukkan URL gambar (mis. https://situs-anda.com/gambar.jpg):');
      if (!url) return;
      const range = quill.getSelection(true);
      quill.insertEmbed(range.index, 'image', url, 'user');
      quill.setSelection(range.index + 1);
    });

    document.querySelector('form').addEventListener('submit', function () {
      input.value = quill.root.innerHTML;
    });
  })();
</script>
@endsection
