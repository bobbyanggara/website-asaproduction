@extends('layouts.admin')
@section('title', 'Keunggulan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 gap-3">
  <p class="text-muted mb-0">Isi kolom "Ikon (kelas CSS)" dengan kelas flaticon bawaan template (contoh: <code>flaticon-toilets1</code>), atau upload gambar ikon sendiri.</p>
  <a href="{{ route('admin.services.create') }}" class="btn btn-dark text-nowrap"><i class="bi bi-plus-lg me-1"></i>Tambah Keunggulan</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead><tr><th class="ps-3">Ikon</th><th>Judul</th><th>Urutan</th><th>Status</th><th class="text-end pe-3">Aksi</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td class="ps-3">
            @if($item->icon && str_contains($item->icon, '/'))
              <img src="{{ asset('storage/'.$item->icon) }}" class="thumb-preview">
            @else
              <code>{{ $item->icon }}</code>
            @endif
          </td>
          <td>{{ $item->title }}</td>
          <td>{{ $item->order_index }}</td>
          <td>@if($item->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
          <td class="text-end pe-3">
            <a href="{{ route('admin.services.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form action="{{ route('admin.services.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus keunggulan ini?');">
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
