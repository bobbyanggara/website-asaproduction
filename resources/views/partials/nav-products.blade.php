{{--
  Item dropdown "Produk" pada navbar — dibaca dari tabel `products`, jadi
  produk baru yang ditambah admin (Admin > Semua Produk) otomatis muncul di
  sini tanpa edit kode. Dipakai oleh partials/site-nav.blade.php dan
  home.blade.php supaya navbar di semua halaman selalu sama.

  Subtitle dua bahasa: memakai atribut bawaan i18n-lib `data-i18n-lang`
  (elemen hanya tampil pada bahasa yang cocok) — sengaja BUKAN data-i18n,
  karena data-i18n akan menimpa textContent dengan kunci kamus yang tidak
  ada untuk produk buatan admin.

  Kalau belum ada produk aktif sama sekali, seluruh menu "Produk" ikut
  disembunyikan supaya tidak muncul dropdown kosong.
--}}
@php($__navProducts = \App\Models\Product::navProducts())
@if($__navProducts->isNotEmpty())
<li class="has-dropdown">
  <button type="button" class="dropdown-toggle"><span data-i18n="nav.produkToggle">Produk</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></button>
  <ul class="dropdown-menu dropdown-menu--services">
    @foreach($__navProducts as $__navProduct)
    <li>
      <a href="{{ route('produk.show', $__navProduct->slug) }}">
        <span class="dropdown-menu_icon"><i class="fa {{ $__navProduct->navIconClass() }}" aria-hidden="true"></i></span>
        <span class="dropdown-menu_text">
          <span class="dropdown-menu_title">{{ $__navProduct->name }}</span>
          @if($__navProduct->nav_description)
          <span class="dropdown-menu_subtitle" data-i18n-lang="id">{{ $__navProduct->nav_description }}</span>
          <span class="dropdown-menu_subtitle" data-i18n-lang="en" style="display:none;">{{ $__navProduct->nav_description_en ?: $__navProduct->nav_description }}</span>
          @endif
        </span>
      </a>
    </li>
    @endforeach
  </ul>
</li>
@endif
