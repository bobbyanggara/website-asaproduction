{{--
  Item dropdown "Solusi" pada navbar — dibaca dari tabel `solutions`, jadi
  halaman solusi baru yang ditambah admin (Admin > Semua Solusi) otomatis muncul
  di sini tanpa edit kode. Dipakai oleh partials/site-nav.blade.php dan
  home.blade.php supaya navbar di semua halaman selalu sama.

  Nama & subtitle dua bahasa memakai atribut bawaan i18n-lib `data-i18n-lang`
  (elemen hanya tampil pada bahasa yang cocok) — sengaja BUKAN data-i18n,
  karena data-i18n akan menimpa textContent dengan kunci kamus yang tidak
  ada untuk halaman buatan admin.

  Kalau belum ada solusi aktif sama sekali, seluruh menu "Solusi" ikut
  disembunyikan supaya tidak muncul dropdown kosong.
--}}
@php($__navSolutions = \App\Models\Solution::navSolutions())
@if($__navSolutions->isNotEmpty())
<li class="has-dropdown">
  <button type="button" class="dropdown-toggle"><span data-i18n="nav.servicesToggle">Solusi</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></button>
  <ul class="dropdown-menu dropdown-menu--services">
    @foreach($__navSolutions as $__navSolution)
    <li>
      <a href="{{ route('solusi.show', $__navSolution->slug) }}">
        <span class="dropdown-menu_icon"><i class="fa {{ $__navSolution->navIconClass() }}" aria-hidden="true"></i></span>
        <span class="dropdown-menu_text">
          <span class="dropdown-menu_title">@bipair($__navSolution->name, $__navSolution->name_en)</span>
          @if($__navSolution->nav_description)
          <span class="dropdown-menu_subtitle" data-i18n-lang="id">{{ $__navSolution->nav_description }}</span>
          <span class="dropdown-menu_subtitle" data-i18n-lang="en" style="display:none;">{{ $__navSolution->nav_description_en ?: $__navSolution->nav_description }}</span>
          @endif
        </span>
      </a>
    </li>
    @endforeach
  </ul>
</li>
@endif
