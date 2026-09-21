<?php

namespace Database\Seeders;

use App\Models\AppMobileBenefit;
use App\Models\AppMobilePage;
use App\Models\CustomSystemBenefit;
use App\Models\CustomSystemPage;
use App\Models\WebsiteBenefit;
use App\Models\WebsitePage;
use Illuminate\Database\Seeder;

/**
 * Isi konten dasar (hero + 3 poin Keunggulan) untuk ketiga halaman
 * /solusi/*. Aman dijalankan berkali-kali: hero pakai updateOrInsert
 * (1 baris saja), dan Keunggulan hanya dibuat kalau tabelnya masih kosong
 * supaya tidak menumpuk data ganda setiap kali seeder dijalankan ulang.
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=Database\\Seeders\\SolusiPagesContentSeeder
 */
class SolusiPagesContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAppMobile();
        $this->seedWebsite();
        $this->seedCustomSystem();
    }

    private function seedAppMobile(): void
    {
        AppMobilePage::current()->update([
            'hero_title' => 'Asa Production, Solusi Tepat untuk Pengembangan Aplikasi Android & iOS yang Mudah dan Efektif',
            'hero_description' => 'Asa Production siap membantu Anda mengembangkan aplikasi Android & iOS dengan mudah dan efektif. Dengan tim ahli dan teknologi terkini, kami menghadirkan solusi aplikasi yang responsif, user-friendly, dan sesuai dengan kebutuhan bisnis Anda.',
        ]);

        if (AppMobileBenefit::count() === 0) {
            AppMobileBenefit::insert([
                [
                    'icon' => 'fa-graduation-cap',
                    'title' => 'Tim Ahli dengan Teknologi Terkini',
                    'description' => 'Asa Production mengandalkan tim pengembang berpengalaman yang menggunakan teknologi modern — seperti Flutter, React Native, dan framework terbaru — untuk membangun aplikasi yang responsif dan user-friendly.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-comments',
                    'title' => 'Pendekatan Kustom dan Personalisasi Solusi',
                    'description' => 'Asa Production tidak hanya menawarkan aplikasi "one size fits all", melainkan solusi yang dirancang khusus sesuai kebutuhan bisnis setiap klien.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-line-chart',
                    'title' => 'Proses Pengembangan Agile dan Dukungan Berkelanjutan',
                    'description' => 'Menggunakan metodologi agile, Asa Production memastikan proses pengembangan aplikasi yang fleksibel dan iteratif, memungkinkan penyesuaian fitur secara real-time berdasarkan feedback klien.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    private function seedWebsite(): void
    {
        WebsitePage::current()->update([
            'hero_title' => 'Asa Production, Solusi Tepat untuk Pembuatan Website yang Cepat, Rapi, dan Profesional',
            'hero_description' => 'Asa Production membantu bisnis Anda tampil profesional di internet lewat website yang cepat, aman, dan mudah dikelola sendiri. Cocok untuk company profile, toko online, landing page promosi, hingga sistem berbasis web.',
        ]);

        if (WebsiteBenefit::count() === 0) {
            WebsiteBenefit::insert([
                [
                    'icon' => 'fa-paint-brush',
                    'title' => 'Desain Modern dan Responsif',
                    'description' => 'Setiap website dirancang dengan tampilan modern dan rapi, serta otomatis menyesuaikan diri di layar HP, tablet, maupun desktop.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-search',
                    'title' => 'SEO Friendly Sejak Awal',
                    'description' => 'Struktur website dibangun mengikuti kaidah SEO dasar sehingga lebih mudah ditemukan calon pelanggan lewat pencarian Google.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-sliders',
                    'title' => 'Mudah Dikelola Sendiri',
                    'description' => 'Dilengkapi panel admin sederhana sehingga tim Anda bisa mengubah konten, foto, dan artikel kapan saja tanpa perlu bantuan developer.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    private function seedCustomSystem(): void
    {
        CustomSystemPage::current()->update([
            'hero_title' => 'Asa Production, Solusi Tepat untuk Pengembangan Sistem Kustom Sesuai Kebutuhan Bisnis Anda',
            'hero_description' => 'Asa Production merancang dan membangun sistem/software kustom untuk mengotomasi proses bisnis Anda — mulai dari sistem absensi, manajemen inventori, hingga aplikasi internal khusus yang dirancang sesuai alur kerja perusahaan Anda.',
        ]);

        if (CustomSystemBenefit::count() === 0) {
            CustomSystemBenefit::insert([
                [
                    'icon' => 'fa-sitemap',
                    'title' => 'Dirancang Sesuai Alur Kerja Anda',
                    'description' => 'Sistem dibangun dari analisis proses bisnis Anda, bukan template siap pakai, sehingga benar-benar pas dengan cara tim Anda bekerja.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-puzzle-piece',
                    'title' => 'Terintegrasi dan Bisa Berkembang',
                    'description' => 'Setiap modul dirancang agar mudah dihubungkan dengan sistem lain dan ditambah fitur baru seiring bisnis Anda tumbuh.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-life-ring',
                    'title' => 'Pendampingan Setelah Sistem Live',
                    'description' => 'Tim kami tetap mendampingi setelah sistem berjalan untuk pelatihan pengguna, perbaikan, dan penyesuaian kebutuhan baru.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
