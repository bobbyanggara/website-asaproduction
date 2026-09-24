@extends('layouts.admin')
@section('title', 'Terjemahan (EN)')

@section('content')
<div class="alert alert-info">
  <strong>Terjemahan otomatis aktif.</strong> Setiap kali Anda menyimpan konten di Admin (artikel, layanan, FAQ, halaman solusi, produk, dll.),
  versi Inggrisnya dibuat otomatis. Halaman ini untuk mengisi versi Inggris <strong>konten lama</strong> yang dibuat sebelum fitur ini ada —
  cukup sekali klik.
</div>

@if(!$configured)
  <div class="alert alert-danger">
    <strong>GROQ_API_KEY belum diisi di file <code>.env</code>.</strong> Tanpa key ini terjemahan tidak berjalan (situs tetap normal, hanya tampil Bahasa Indonesia di mode English).
    Buat key gratis di <a href="https://console.groq.com/keys" target="_blank" rel="noopener">console.groq.com</a>, lalu isi <code>GROQ_API_KEY=...</code>.
  </div>
@endif
@if(!$enabled)
  <div class="alert alert-warning">Terjemahan otomatis saat simpan sedang dimatikan (<code>AUTO_TRANSLATE=false</code> di <code>.env</code>).</div>
@endif

<div class="card shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <strong>Status terjemahan per jenis konten</strong>
    <button type="button" class="btn btn-dark btn-sm" id="btnAll" @disabled(!$configured)>
      <i class="bi bi-translate me-1"></i> Terjemahkan semua yang kosong
    </button>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr><th>Konten</th><th class="text-end">Total</th><th class="text-end">Belum ada EN</th><th style="width:1%"></th></tr>
      </thead>
      <tbody>
        @foreach($rows as $row)
        <tr data-key="{{ $row['key'] }}">
          <td>{{ $row['label'] }}</td>
          <td class="text-end">{{ $row['total'] }}</td>
          <td class="text-end"><span class="badge {{ $row['pending'] ? 'text-bg-warning' : 'text-bg-success' }}" data-pending>{{ $row['pending'] ?: 'Lengkap' }}</span></td>
          <td class="text-nowrap">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-run @disabled(!$configured || !$row['pending'])>Terjemahkan</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<pre class="bg-dark text-light rounded p-3 mt-3 small" id="log" style="display:none;max-height:260px;overflow:auto;"></pre>

<script>
(function () {
  var url = @json(route('admin.translations.run'));
  var token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : @json(csrf_token());
  var logEl = document.getElementById('log');
  var busy = false;

  function log(msg) {
    logEl.style.display = 'block';
    logEl.textContent += msg + '\n';
    logEl.scrollTop = logEl.scrollHeight;
  }

  function setBusy(v) {
    busy = v;
    document.querySelectorAll('[data-run], #btnAll').forEach(function (b) { if (v) { b.disabled = true; } });
  }

  async function runModel(tr) {
    var key = tr.getAttribute('data-key');
    var label = tr.children[0].textContent.trim();
    var after = 0, done = 0, failed = 0, pending = null;
    log('▶ ' + label);
    while (true) {
      var res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ model: key, after_id: after })
      });
      if (!res.ok) { log('  ✖ Gagal (HTTP ' + res.status + '). Coba lagi.'); return false; }
      var data = await res.json();
      done += data.done; failed += data.failed; after = data.last_id; pending = data.pending;
      var badge = tr.querySelector('[data-pending]');
      badge.textContent = pending ? pending : 'Lengkap';
      badge.className = 'badge ' + (pending ? 'text-bg-warning' : 'text-bg-success');
      if (!data.has_more) { break; }
    }
    log('  ✔ ' + done + ' data diterjemahkan' + (failed ? ', ' + failed + ' gagal (cek storage/logs/laravel.log)' : ''));
    return failed === 0;
  }

  async function runRows(rows) {
    if (busy) { return; }
    setBusy(true);
    for (var i = 0; i < rows.length; i++) { await runModel(rows[i]); }
    log('Selesai. Muat ulang halaman untuk melihat status terbaru.');
    setBusy(false);
  }

  document.querySelectorAll('[data-run]').forEach(function (btn) {
    btn.addEventListener('click', function () { runRows([btn.closest('tr')]); });
  });
  var all = document.getElementById('btnAll');
  if (all) {
    all.addEventListener('click', function () {
      var rows = Array.prototype.filter.call(document.querySelectorAll('tbody tr'), function (tr) {
        return tr.querySelector('[data-run]') && !tr.querySelector('[data-run]').disabled;
      });
      runRows(rows);
    });
  }
})();
</script>
@endsection
