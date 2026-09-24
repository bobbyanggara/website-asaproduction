@extends('layouts.admin')
@section('title', 'FAQ - '.$product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Halaman {{ $product->name }}</a>
  <a href="{{ route('admin.product-items.create', [$product, 'faqs']) }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i>Tambah FAQ</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead><tr><th class="ps-3">Pertanyaan</th><th>Jawaban</th><th>Urutan</th><th>Status</th><th class="text-end pe-3">Aksi</th></tr></thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td class="ps-3">{{ $item->question }}</td>
          <td>{{ \Illuminate\Support\Str::limit($item->answer, 60) }}</td>
          <td>{{ $item->order_index }}</td>
          <td>@if($item->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Nonaktif</span>@endif</td>
          <td class="text-end pe-3">
            <a href="{{ route('admin.product-items.edit', [$product, 'faqs', $item->id]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form action="{{ route('admin.product-items.destroy', [$product, 'faqs', $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus FAQ ini?');">
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
