@extends('layouts.admin')
@section('title', 'Portofolio - '.$solution->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="{{ route('admin.solutions.edit', $solution) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman {{ $solution->name }}</a>
  <a href="{{ route('admin.solution-items.create', [$solution, 'portfolios']) }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Tambah Portofolio</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead><tr><th class="ps-3">Thumbnail</th><th>Judul</th><th>Urutan</th><th>Status</th><th class="text-end pe-3">Aksi</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td class="ps-3"><img src="{{ asset('storage/'.$item->thumbnail) }}" class="thumb-preview"></td>
          <td>{{ $item->title ?: '—' }}</td>
          <td>{{ $item->order_index }}</td>
          <td>@if($item->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
          <td class="text-end pe-3">
            <a href="{{ route('admin.solution-items.edit', [$solution, 'portfolios', $item->id]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form action="{{ route('admin.solution-items.destroy', [$solution, 'portfolios', $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus portofolio ini?');">
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
