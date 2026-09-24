<?php

namespace App\Http\Controllers;

use App\Models\AbsenAsaBenefit;
use App\Models\AbsenAsaClient;
use App\Models\AbsenAsaFaq;
use App\Models\AbsenAsaPage;
use App\Models\Certificate;
use App\Models\Service;
use App\Models\Setting;

class AbsenAsaController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        $page = AbsenAsaPage::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan footer landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        // Poin "Keunggulan" khusus halaman ini, dikelola dari Admin > Produk > absen-asa > Keunggulan.
        $benefits = AbsenAsaBenefit::active()->ordered()->get();

        // Logo klien khusus halaman ini, dikelola dari Admin > Produk > absen-asa > Klien.
        $clients = AbsenAsaClient::active()->ordered()->get();

        // FAQ khusus halaman ini, dikelola dari Admin > Produk > absen-asa > FAQ.
        $faqs = AbsenAsaFaq::active()->ordered()->get();

        return view('produk.absen-asa', compact(
            'setting', 'page', 'services', 'certificates', 'benefits', 'clients', 'faqs'
        ));
    }
}
