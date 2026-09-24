<?php

namespace App\Http\Controllers;

use App\Models\KlinikAsaBenefit;
use App\Models\KlinikAsaClient;
use App\Models\KlinikAsaFaq;
use App\Models\KlinikAsaPage;
use App\Models\Certificate;
use App\Models\Service;
use App\Models\Setting;

class KlinikAsaController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $page = KlinikAsaPage::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan footer landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        // Poin "Keunggulan" khusus halaman ini, dikelola dari Admin > Produk > klinik-asa > Keunggulan.
        $benefits = KlinikAsaBenefit::active()->ordered()->get();

        // Logo klien khusus halaman ini, dikelola dari Admin > Produk > klinik-asa > Klien.
        $clients = KlinikAsaClient::active()->ordered()->get();

        // FAQ khusus halaman ini, dikelola dari Admin > Produk > klinik-asa > FAQ.
        $faqs = KlinikAsaFaq::active()->ordered()->get();

        return view('produk.klinik-asa', compact(
            'setting', 'page', 'services', 'certificates', 'benefits', 'clients', 'faqs'
        ));
    }
}
