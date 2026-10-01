{{-- Tombol "Tambah ..." gaya modern (pil gradasi). Dipakai di semua halaman admin.
     Membuka popup <x-admin.modal> dengan id yang sama.
     Contoh: <x-admin.add-button target="addServiceModal">Tambah Layanan</x-admin.add-button>
     Gaya (.btn-add) ada di layouts/admin.blade.php. --}}
@props(['target'])
<button type="button" data-dialog-open="#{{ $target }}" {{ $attributes->merge(['class' => 'btn-add']) }}>
  <span class="plus"><i class="bi bi-plus-lg"></i></span><span>{{ $slot }}</span>
</button>
