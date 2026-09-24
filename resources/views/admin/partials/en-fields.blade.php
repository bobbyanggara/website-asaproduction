{{--
  Panel "Versi English" generik untuk form admin.

  Cara pakai (taruh di dalam <form>, sebelum tombol Simpan):
      @include('admin.partials.en-fields', ['item' => $item])
      @include('admin.partials.en-fields', ['item' => $page, 'skip' => ['nav_description']])

  Daftar field diambil dari $translatable milik model (trait AutoTranslates),
  jadi partial ini tidak perlu diubah kalau field baru ditambahkan ke model.

  Admin TIDAK wajib mengisi apa pun di sini: kolom kosong otomatis diterjemahkan
  dari teks Indonesia saat klik Simpan. Panel ini hanya untuk memperbaiki hasil
  terjemahan secara manual — isian manual tidak akan ditimpa otomatis, kecuali teks
  Indonesianya berubah sementara kolom ini dibiarkan seperti semula.
--}}
@php
  $__enFields = (isset($item) && is_object($item) && method_exists($item, 'translatableFields'))
      ? array_diff_key($item::translatableFields(), array_flip($skip ?? []))
      : [];
  $__enPending = 0;
  foreach ($__enFields as $__f => $__meta) {
      if (filled($item->{$__f}) && blank($item->{$__f.'_en'})) { $__enPending++; }
  }
@endphp
@if($__enFields)
<details class="card shadow-sm mt-3">
  <summary class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center" style="cursor:pointer;">
    <span><i class="bi bi-translate me-1"></i> Versi English <span class="text-muted fw-normal small">— otomatis diterjemahkan saat disimpan</span></span>
    @if($item->exists && $__enPending)
      <span class="badge text-bg-warning">Belum diterjemahkan</span>
    @elseif($item->exists)
      <span class="badge text-bg-success">Sudah diterjemahkan</span>
    @endif
  </summary>
  <div class="card-body row g-3">
    <div class="col-12">
      <div class="form-text mt-0">
        Cukup isi bahasa Indonesia di atas. Versi Inggris dibuat otomatis saat Anda klik <strong>Simpan</strong>.
        Kolom di bawah hanya untuk memperbaiki hasilnya secara manual; kalau dikosongkan, akan diterjemahkan otomatis lagi.
      </div>
    </div>
    @foreach($__enFields as $__f => $__meta)
      <div class="{{ $__meta['multiline'] ? 'col-12' : 'col-md-6' }}">
        <label class="form-label">{{ $__meta['label'] }} (EN)</label>
        @if($__meta['multiline'])
          <textarea name="{{ $__f }}_en" class="form-control" rows="{{ $__f === 'content' ? 8 : 3 }}" placeholder="Otomatis diterjemahkan saat disimpan">{{ old($__f.'_en', $item->{$__f.'_en'}) }}</textarea>
        @else
          <input type="text" name="{{ $__f }}_en" class="form-control" value="{{ old($__f.'_en', $item->{$__f.'_en'}) }}" placeholder="Otomatis diterjemahkan saat disimpan">
        @endif
      </div>
    @endforeach
  </div>
</details>
@endif
