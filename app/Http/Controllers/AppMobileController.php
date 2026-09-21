<?php

namespace App\Http\Controllers;

use App\Models\AppMobileBenefit;
use App\Models\AppMobileClient;
use App\Models\AppMobileFaq;
use App\Models\Certificate;
use App\Models\AppMobilePage;
use App\Models\AppMobilePortfolio;
use App\Models\Service;
use App\Models\Setting;

class AppMobileController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $page = AppMobilePage::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan footer landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        // Poin "Keunggulan" khusus halaman App Mobile, dikelola dari
        // Admin > Solusi > App Mobile > Keunggulan.
        $benefits = AppMobileBenefit::active()->ordered()->get();

        // Logo klien khusus halaman App Mobile, dikelola dari
        // Admin > Solusi > App Mobile > Klien (terpisah dari menu Partner di beranda).
        $clients = AppMobileClient::active()->ordered()->get();

        // Portofolio khusus halaman App Mobile, dikelola dari
        // Admin > Solusi > App Mobile > Portofolio (tabel sendiri, terpisah dari
        // Portofolio global di beranda/halaman /portfolio dan dari punya
        // Website/Sistem Kustom — tidak lagi bergantung pada string kategori).
        $portfolios = AppMobilePortfolio::active()->ordered()->take(3)->get();

        // FAQ khusus halaman App Mobile, dikelola dari
        // Admin > Solusi > App Mobile > FAQ.
        $faqs = AppMobileFaq::active()->ordered()->get();

        return view('solusi.app-mobile', compact(
            'setting', 'page', 'services', 'certificates', 'benefits', 'clients', 'portfolios', 'faqs'
        ));
    }
}
