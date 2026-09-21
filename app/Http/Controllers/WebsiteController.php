<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Service;
use App\Models\Setting;
use App\Models\WebsiteBenefit;
use App\Models\WebsiteClient;
use App\Models\WebsiteFaq;
use App\Models\WebsitePage;
use App\Models\WebsitePortfolio;

class WebsiteController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $page = WebsitePage::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan footer landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        // Poin "Keunggulan" khusus halaman Website, dikelola dari
        // Admin > Solusi > Website > Keunggulan.
        $benefits = WebsiteBenefit::active()->ordered()->get();

        // Logo klien khusus halaman Website, dikelola dari
        // Admin > Solusi > Website > Klien (terpisah dari menu Partner di beranda).
        $clients = WebsiteClient::active()->ordered()->get();

        // Portofolio khusus halaman Website, dikelola dari
        // Admin > Solusi > Website > Portofolio (tabel sendiri, terpisah dari
        // Portofolio global di beranda/halaman /portfolio dan dari punya
        // App Mobile/Sistem Kustom — tidak lagi bergantung pada string kategori).
        $portfolios = WebsitePortfolio::active()->ordered()->take(3)->get();

        // FAQ khusus halaman Website, dikelola dari
        // Admin > Solusi > Website > FAQ.
        $faqs = WebsiteFaq::active()->ordered()->get();

        return view('solusi.website', compact(
            'setting', 'page', 'services', 'certificates', 'benefits', 'clients', 'portfolios', 'faqs'
        ));
    }
}
