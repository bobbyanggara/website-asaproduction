{{-- Daftar tautan solusi di footer (kolom "Layanan"), dibaca dari tabel `solutions`. --}}
@foreach(\App\Models\Solution::navSolutions() as $__footSolution)
<li><a href="{{ route('solusi.show', $__footSolution->slug) }}">@bipair($__footSolution->name, $__footSolution->name_en)</a></li>
@endforeach
