<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CustomSystemBenefit;
use App\Models\CustomSystemClient;
use App\Models\CustomSystemFaq;
use App\Models\CustomSystemPage;
use App\Models\CustomSystemPortfolio;
use App\Models\Service;
use App\Models\Setting;

class CustomSystemController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $page = CustomSystemPage::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan footer landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        // Poin "Keunggulan" khusus halaman Sistem Kustom, dikelola dari
        // Admin > Solusi > Sistem Kustom > Keunggulan.
        $benefits = CustomSystemBenefit::active()->ordered()->get();

        // Logo klien khusus halaman Sistem Kustom, dikelola dari
        // Admin > Solusi > Sistem Kustom > Klien (terpisah dari menu Partner di beranda).
        $clients = CustomSystemClient::active()->ordered()->get();

        // Portofolio khusus halaman Sistem Kustom, dikelola dari
        // Admin > Solusi > Sistem Kustom > Portofolio (tabel sendiri, terpisah
        // dari Portofolio global di beranda/halaman /portfolio dan dari punya
        // Website/App Mobile — tidak lagi bergantung pada string kategori).
        $portfolios = CustomSystemPortfolio::active()->ordered()->take(3)->get();

        // FAQ khusus halaman Sistem Kustom, dikelola dari
        // Admin > Solusi > Sistem Kustom > FAQ.
        $faqs = CustomSystemFaq::active()->ordered()->get();

        return view('solusi.custom-system', compact(
            'setting', 'page', 'services', 'certificates', 'benefits', 'clients', 'portfolios', 'faqs'
        ));
    }
}
