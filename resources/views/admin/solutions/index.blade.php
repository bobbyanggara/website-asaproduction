@extends('layouts.admin')
@section('title', 'Semua Solusi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0">Halaman solusi yang <strong>aktif</strong> otomatis tampil di dropdown "Solusi" pada navbar &amp; footer dan punya halaman sendiri di <code>/solusi/{slug}</code>.</p>
  <a href="{{ route('admin.solutions.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Tambah Solusi</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead>
        <tr>
          <th class="ps-3">Halaman Solusi</th>
          <th>URL Halaman</th>
          <th class="text-center">Keunggulan</th>
          <th class="text-center">Klien</th>
          <th class="text-center">Portofolio</th>
          <th class="text-center">FAQ</th>
          <th>Urutan</th>
          <th>Status</th>
          <th class="text-end pe-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td class="ps-3">
            <i class="fa {{ $item->navIconClass() }} me-2" aria-hidden="true"></i><strong>{{ $item->name }}</strong>
            @if($item->nav_description)<div class="small text-muted">{{ \Illuminate\Support\Str::limit($item->nav_description, 60) }}</div>@endif
          </td>
          <td>
            @if($item->is_active)
              <a href="{{ route('solusi.show', $item->slug) }}" target="_blank" rel="noopener">/solusi/{{ $item->slug }}</a>
            @else
              <span class="text-muted">/solusi/{{ $item->slug }}</span>
            @endif
          </td>
          <td class="text-center">{{ $item->benefits_count }}</td>
          <td class="text-center">{{ $item->clients_count }}</td>
          <td class="text-center">{{ $item->portfolios_count }}</td>
          <td class="text-center">{{ $item->faqs_count }}</td>
          <td>{{ $item->order_index }}</td>
          <td>@if($item->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
          <td class="text-end pe-3">
            <a href="{{ route('admin.solutions.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form action="{{ route('admin.solutions.destroy', $item) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Hapus halaman solusi &quot;{{ addslashes($item->name) }}&quot;? Halaman publiknya, serta semua Keunggulan, Klien, Portofolio, dan FAQ miliknya ikut terhapus permanen.');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-muted p-3">Belum ada halaman solusi. Klik "Tambah Solusi" untuk membuat yang pertama.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
