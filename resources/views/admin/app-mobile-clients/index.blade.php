@extends('layouts.admin')
@section('title', 'Klien - Jasa App Mobile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <p class="text-muted mb-0">Logo klien khusus untuk section "Clients" di halaman <a href="{{ route('solusi.app-mobile') }}" target="_blank" rel="noopener">/solusi/jasa-pembuatan-app-mobile</a>. Tidak diambil dari menu Partner.</p>
  <a href="{{ route('admin.app-mobile-clients.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Tambah Klien</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead><tr><th class="ps-3">Logo</th><th>Nama</th><th>Urutan</th><th>Status</th><th class="text-end pe-3">Aksi</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td class="ps-3"><img src="{{ asset('storage/'.$item->logo) }}" class="thumb-preview"></td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->order_index }}</td>
          <td>@if($item->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
          <td class="text-end pe-3">
            <a href="{{ route('admin.app-mobile-clients.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form action="{{ route('admin.app-mobile-clients.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus klien ini?');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-muted p-3">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
