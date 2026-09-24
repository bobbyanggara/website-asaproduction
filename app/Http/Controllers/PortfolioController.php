<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;

class PortfolioController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $portfolios = Portfolio::active()->ordered()->get();

        // Daftar kategori unik dari data yang ada, dipakai untuk tab filter.
        // Item tanpa kategori tetap tampil di tab "Semua".
        $categories = $portfolios
            ->pluck('category')
            ->filter()
            ->unique()
            ->values();

        // Nama kategori versi Inggris untuk label tab filter. Nilai `data-category`
        // tetap teks Indonesia (dipakai JS untuk mencocokkan kartu), hanya label yang dua bahasa.
        $categoryEn = $portfolios
            ->filter(fn ($p) => filled($p->category) && filled($p->category_en))
            ->unique('category')
            ->pluck('category_en', 'category');

        // Dibutuhkan supaya footer di halaman ini sama persis dengan footer landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        return view('portfolio', compact('setting', 'portfolios', 'categories', 'categoryEn', 'services', 'certificates'));
    }
}
