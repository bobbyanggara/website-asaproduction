<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;

class ProductController extends Controller
{
    /**
     * Halaman produk publik dinamis: /produk/{slug}.
     *
     * Satu controller + satu view untuk semua produk. Produk baru yang
     * ditambahkan admin langsung bisa dibuka di sini tanpa route/kode baru.
     * Produk yang dinonaktifkan (is_active = false) dianggap tidak ada (404),
     * konsisten dengan hilangnya produk itu dari navbar.
     */
    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $setting = Setting::current();

        // Dibutuhkan supaya footer di halaman ini sama persis dengan landing page.
        $services = Service::active()->ordered()->get();
        $certificates = Certificate::active()->ordered()->get();

        $page = $product;
        $benefits = $product->benefits()->where('is_active', true)->get();
        $clients = $product->clients()->where('is_active', true)->get();
        $faqs = $product->faqs()->where('is_active', true)->get();

        return view('produk.show', compact(
            'setting', 'product', 'page', 'services', 'certificates', 'benefits', 'clients', 'faqs'
        ));
    }
}
