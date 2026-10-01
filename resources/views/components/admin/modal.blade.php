{{-- Popup (dialog bawaan browser) seragam untuk semua halaman admin.
     Dibuka lewat <x-admin.add-button target="id"> atau elemen apa pun dengan data-dialog-open="#id".
     Ditutup lewat tombol X, elemen data-dialog-close, Esc, atau klik di luar kotak.
     open=true -> otomatis terbuka saat halaman dimuat (mis. setelah error validasi).
     Contoh:
       <x-admin.modal id="addServiceModal" title="Tambah Layanan" :open="$errors->service->any()">
         <form method="POST" action="..." class="app-dialog_form"> ... </form>
       </x-admin.modal> --}}
@props(['id', 'title', 'icon' => 'bi-plus-circle-fill', 'open' => false])
<dialog class="app-dialog" id="{{ $id }}" @if($open) data-open-on-load @endif>
  <div class="app-dialog_head">
    <h6><i class="bi {{ $icon }} text-primary me-1"></i> {{ $title }}</h6>
    <button type="button" class="app-dialog_x" data-dialog-close aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
  </div>
  <div class="app-dialog_body">{{ $slot }}</div>
</dialog>
