<?php

namespace Database\Seeders;

use App\Models\AppMobileClient;
use App\Models\CustomSystemClient;
use App\Models\Portfolio;
use App\Models\WebsiteClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Isi placeholder untuk section "Temukan inspirasimu" (Portfolio, 3 item)
 * dan section "Clients" (logo klien, 3 item) di ketiga halaman /solusi/*
 * (Website, App Mobile, Sistem Kustom). Semua cuma placeholder sementara —
 * silakan diganti/ditambah lewat Admin > Halaman Solusi > [layanan] >
 * Portofolio (menu Portofolio global, filter kategori) & Klien.
 *
 * Gambar dipakai ulang dari aset template yang sudah ada di
 * public/images (mockup landing page untuk portfolio, logo abu-abu
 * generik untuk client) — bukan gambar final, murni placeholder visual
 * supaya section-nya tidak kosong sebelum diisi manual.
 *
 * Aman dijalankan berkali-kali: hanya mengisi kalau datanya (per
 * kategori/tabel) masih kosong, tidak menumpuk data ganda.
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=Database\\Seeders\\SolusiPortfolioClientSeeder
 */
class SolusiPortfolioClientSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPortfolios();
        $this->seedClients();
    }

    private function seedPortfolios(): void
    {
        // 3 mockup landing page yang sudah ada di template asli, dipakai
        // ulang sebagai placeholder visual untuk ketiga kategori.
        $images = [
            ['thumb' => 'page-1_img05.jpg', 'full' => 'page-1_img05_original.jpg'],
            ['thumb' => 'page-1_img06.jpg', 'full' => 'page-1_img06_original.jpg'],
            ['thumb' => 'page-1_img08.jpg', 'full' => 'page-1_img08_original.jpg'],
        ];

        $categories = [
            'Jasa Pembuatan Website' => [
                'Contoh Proyek Website 1',
                'Contoh Proyek Website 2',
                'Contoh Proyek Website 3',
            ],
            'Jasa Pembuatan Aplikasi' => [
                'Contoh Proyek Aplikasi 1',
                'Contoh Proyek Aplikasi 2',
                'Contoh Proyek Aplikasi 3',
            ],
            'Jasa Sistem Custom' => [
                'Contoh Proyek Sistem Kustom 1',
                'Contoh Proyek Sistem Kustom 2',
                'Contoh Proyek Sistem Kustom 3',
            ],
        ];

        foreach ($categories as $category => $titles) {
            if (Portfolio::where('category', $category)->count() > 0) {
                continue;
            }

            foreach ($titles as $i => $title) {
                $slug = Str::slug($category).'-placeholder-'.($i + 1);
                $thumbTarget = "portfolio/{$slug}_thumb.jpg";
                $fullTarget = "portfolio/{$slug}_full.jpg";

                $this->copyImage($images[$i]['thumb'], $thumbTarget);
                $this->copyImage($images[$i]['full'], $fullTarget);

                Portfolio::create([
                    'title' => $title,
                    'category' => $category,
                    'description' => 'Placeholder portofolio — silakan ganti dengan hasil proyek asli lewat menu Portofolio di admin.',
                    'thumbnail' => $thumbTarget,
                    'image' => $fullTarget,
                    'order_index' => $i + 1,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seedClients(): void
    {
        // 3 logo generik (abu-abu, netral) yang sudah ada di template asli,
        // dipakai ulang sebagai placeholder untuk ketiga halaman.
        $logos = ['page-1_img10.jpg', 'page-1_img11.jpg', 'page-1_img12.jpg'];
        $names = ['Klien Contoh 1', 'Klien Contoh 2', 'Klien Contoh 3'];

        $this->seedClientModel(WebsiteClient::class, 'website-clients', $logos, $names);
        $this->seedClientModel(AppMobileClient::class, 'app-mobile-clients', $logos, $names);
        $this->seedClientModel(CustomSystemClient::class, 'custom-system-clients', $logos, $names);
    }

    /**
     * @param  class-string  $model
     */
    private function seedClientModel(string $model, string $folder, array $logos, array $names): void
    {
        if ($model::count() > 0) {
            return;
        }

        foreach ($logos as $i => $logo) {
            $target = "{$folder}/placeholder-".($i + 1).'.jpg';
            $this->copyImage($logo, $target);

            $model::create([
                'name' => $names[$i],
                'logo' => $target,
                'order_index' => $i + 1,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Copy gambar dari public/images (sudah ada di project, bukan folder
     * assets/ yang di beberapa environment tidak ikut ter-deploy) ke
     * storage/app/public, supaya record yang dibuat menunjuk ke file
     * yang benar-benar ada.
     */
    private function copyImage(string $sourceFileName, string $target): void
    {
        if (Storage::disk('public')->exists($target)) {
            return;
        }

        $sourcePath = public_path('images/'.$sourceFileName);

        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($target, File::get($sourcePath));
        }
    }
}
