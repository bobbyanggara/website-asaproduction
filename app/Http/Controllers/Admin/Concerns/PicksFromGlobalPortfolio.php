<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Portfolio;
use Illuminate\Http\Request;

/*
  Dipakai controller portofolio per-halaman solusi (Website, App Mobile,
  Sistem Kustom) supaya admin bisa memilih item dari Portofolio global
  (/admin/portfolio → tabel `portfolios`) tanpa upload gambar ulang.

  Item yang dipilih disalin sebagai baris baru di tabel halaman solusi
  masing-masing, dengan path file yang SAMA (tidak ada file duplikat di
  storage). Setelah disalin, urutan / status aktif / judul bisa diatur
  sendiri lewat Edit tanpa mengubah portofolio global.
*/
trait PicksFromGlobalPortfolio
{
    /** Data untuk picker di form tambah: pilihan global + thumbnail yang sudah dipakai. */
    protected function pickerData(string $modelClass): array
    {
        return [
            'globalPortfolios' => Portfolio::active()->ordered()->get(),
            'usedThumbnails' => $modelClass::pluck('thumbnail')->all(),
        ];
    }

    /** Salin portofolio global terpilih. Return jumlah item yang benar-benar ditambahkan. */
    protected function importGlobalPortfolios(string $modelClass, Request $request): int
    {
        $request->validate([
            'portfolio_ids' => ['required', 'array'],
            'portfolio_ids.*' => ['integer', 'exists:portfolios,id'],
        ]);

        $used = $modelClass::pluck('thumbnail')->all();
        $nextOrder = ((int) $modelClass::max('order_index')) + 1;
        $count = 0;

        $picked = Portfolio::active()->ordered()
            ->whereIn('id', $request->input('portfolio_ids'))
            ->get();

        foreach ($picked as $portfolio) {
            if (in_array($portfolio->thumbnail, $used, true)) {
                continue; // sudah ada di halaman ini, lewati
            }

            $modelClass::create([
                'title' => $portfolio->title,
                'title_en' => $portfolio->title_en,
                'description' => $portfolio->description,
                'description_en' => $portfolio->description_en,
                'link' => $portfolio->link,
                'thumbnail' => $portfolio->thumbnail,
                'image' => $portfolio->image ?: $portfolio->thumbnail,
                'order_index' => $nextOrder++,
                'is_active' => true,
            ]);
            $count++;
        }

        return $count;
    }
}
