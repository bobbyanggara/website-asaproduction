{{--
  Picker portofolio global. Variabel:
  $globalPortfolios (Collection<Portfolio>), $usedThumbnails (array path thumbnail
  yang sudah dipakai di halaman ini). Field: portfolio_ids[].
--}}
@php $categories = $globalPortfolios->pluck('category')->filter()->unique()->values(); @endphp

<div class="card shadow-sm mb-3" id="portfolio-picker">
  <div class="card-body">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div>
        <h6 class="mb-0">Pilih dari Portofolio</h6>
        <small class="text-muted">Ambil dari data di menu Portofolio — tidak perlu upload ulang. Klik gambar untuk memilih (bisa lebih dari satu).</small>
      </div>
      <div class="d-flex align-items-center gap-2">
        @if($categories->count())
        <select class="form-select form-select-sm" id="picker-category" style="width:auto;">
          <option value="">Semua kategori</option>
          @foreach($categories as $cat)<option value="{{ $cat }}">{{ $cat }}</option>@endforeach
        </select>
        @endif
        <span class="badge bg-dark" id="picker-count">0 dipilih</span>
      </div>
    </div>

    @if($globalPortfolios->isEmpty())
      <div class="text-muted">Belum ada portofolio aktif. Tambahkan dulu di menu <a href="{{ route('admin.portfolio.index') }}">Portofolio</a>.</div>
    @else
    <div class="row g-3">
      @foreach($globalPortfolios as $p)
        @php $used = in_array($p->thumbnail, $usedThumbnails, true); @endphp
        <div class="col-6 col-md-3 col-lg-2 picker-item" data-category="{{ $p->category }}">
          <label class="picker-card {{ $used ? 'is-used' : '' }}" title="{{ $p->title }}">
            <input type="checkbox" name="portfolio_ids[]" value="{{ $p->id }}" class="picker-check" {{ $used ? 'disabled' : '' }}>
            <img src="{{ asset('storage/'.$p->thumbnail) }}" alt="{{ $p->title }}" loading="lazy">
            <span class="picker-title">{{ $p->title ?: '—' }}</span>
            @if($p->category)<span class="picker-cat">{{ $p->category }}</span>@endif
            @if($used)<span class="picker-used">Sudah ditambahkan</span>@endif
            <i class="bi bi-check-circle-fill picker-tick"></i>
          </label>
        </div>
      @endforeach
    </div>
    @endif
  </div>
</div>

@push('styles')
<style>
  .picker-card { position:relative; display:block; cursor:pointer; border:2px solid transparent; border-radius:.8rem; overflow:hidden; background:#f8f9fa; }
  .picker-card img { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
  .picker-card .picker-check { position:absolute; opacity:0; pointer-events:none; }
  .picker-title { display:block; padding:.35rem .5rem 0; font-size:.8rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .picker-cat { display:block; padding:0 .5rem .4rem; font-size:.7rem; color:#6c757d; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .picker-tick { position:absolute; top:.4rem; right:.4rem; font-size:1.3rem; color:#198754; background:#fff; border-radius:50%; display:none; line-height:1; }
  .picker-card:has(.picker-check:checked) { border-color:#198754; }
  .picker-card:has(.picker-check:checked) .picker-tick { display:block; }
  .picker-card.is-used { cursor:not-allowed; opacity:.5; }
  .picker-used { position:absolute; left:0; right:0; top:0; background:rgba(0,0,0,.65); color:#fff; font-size:.7rem; text-align:center; padding:.15rem; }
</style>
@endpush

@push('scripts')
<script>
  (function () {
    var root = document.getElementById('portfolio-picker');
    if (!root) return;
    var count = document.getElementById('picker-count');
    var filter = document.getElementById('picker-category');
    function update() {
      var n = root.querySelectorAll('.picker-check:checked').length;
      count.textContent = n + ' dipilih';
    }
    root.addEventListener('change', update);
    if (filter) {
      filter.addEventListener('change', function () {
        root.querySelectorAll('.picker-item').forEach(function (el) {
          el.style.display = (!filter.value || el.dataset.category === filter.value) ? '' : 'none';
        });
      });
    }
  })();
</script>
@endpush
