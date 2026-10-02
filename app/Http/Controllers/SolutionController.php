<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Solution;

class SolutionController extends Controller
{
    /**
     * Halaman solusi publik dinamis: /solusi/{slug}.
     *
     * Satu controller + satu view untuk semua halaman solusi. Halaman baru yang
     * ditambahkan admin langsung bisa dibuka di sini tanpa route/kode baru.
     * Halaman yang dinonaktifkan (is_active = false) dianggap tidak ada (404),
     * konsisten dengan hilangnya halaman itu dari navbar dan footer.
     */
    public function show(Solution $solution)
    {
        abort_unless($solution->is_active, 404);

        $setting = Setting::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        $page = $solution;
        $benefits = $solution->benefits()->where('is_active', true)->get();
        $clients = $solution->clients()->where('is_active', true)->get();
        $portfolios = $solution->portfolios()->where('is_active', true)->take(3)->get();
        $faqs = $solution->faqs()->where('is_active', true)->get();

        return view('solusi.show', compact(
            'setting', 'solution', 'page', 'services', 'certificates', 'benefits', 'clients', 'portfolios', 'faqs'
        ));
    }
}
