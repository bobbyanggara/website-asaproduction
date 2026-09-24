<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Isi konten dasar (hero + Keunggulan + FAQ + logo klien contoh) untuk dua
 * produk bawaan: Absen ASA dan Klinik ASA.
 *
 * Sekarang memakai tabel produk dinamis (products + product_*), bukan lagi
 * tabel terpisah per produk. Produk lain dibuat lewat Admin > Semua Produk.
 *
 * Aman dijalankan berkali-kali:
 *  - produk dibuat/diperbarui berdasarkan slug (firstOrCreate),
 *  - hero hanya diisi kalau MASIH KOSONG, jadi hasil editan admin tidak
 *    tertimpa,
 *  - Keunggulan/FAQ/Klien hanya dibuat kalau produk itu belum punya satu pun
 *    (data hasil migrasi dari tabel lama juga dihormati, tidak digandakan).
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=Database\\Seeders\\ProdukPagesContentSeeder
 */
class ProdukPagesContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAbsenAsa();
        $this->seedKlinikAsa();
    }

    /**
     * Ambil produk berdasarkan slug (buat kalau belum ada), isi hero hanya
     * bila kosong, lalu kembalikan produknya.
     */
    private function product(array $identity, string $heroTitle, string $heroSubtitle): Product
    {
        $product = Product::firstOrCreate(['slug' => $identity['slug']], $identity);

        $product->fill([
            'hero_title' => $product->hero_title ?: $heroTitle,
            'hero_subtitle' => $product->hero_subtitle ?: $heroSubtitle,
        ])->save();

        return $product;
    }

    /** Tambahkan product_id ke tiap baris lalu insert massal. */
    private function insertFor(Product $product, string $relation, array $rows): void
    {
        $product->{$relation}()->insert(array_map(
            fn (array $row) => $row + ['product_id' => $product->id],
            $rows
        ));
    }

    private function seedAbsenAsa(): void
    {
        $product = $this->product(
            ['name' => 'Absen ASA', 'slug' => 'absen-asa', 'nav_icon' => 'fa-clock-o',
             'nav_description' => 'Aplikasi absensi karyawan berbasis GPS & selfie',
             'nav_description_en' => 'Employee attendance app with GPS & selfie verification',
             'order_index' => 1, 'is_active' => true],
            'Absen ASA, Aplikasi Absensi Karyawan Digital yang Akurat dan Mudah Digunakan',
            'Absen ASA membantu perusahaan Anda mencatat kehadiran karyawan secara digital lewat GPS dan verifikasi selfie, lengkap dengan laporan otomatis dan integrasi payroll — anti titip absen dan minim kerja manual admin HR.'
        );

        if ($product->benefits()->count() === 0) {
            $this->insertFor($product, 'benefits', [
                [
                    'icon' => 'fa-map-marker',
                    'title' => 'Absen GPS & Selfie',
                    'description' => 'Karyawan absen langsung dari HP masing-masing dengan validasi lokasi (GPS) dan foto selfie, sehingga meminimalkan praktik titip absen.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-line-chart',
                    'title' => 'Laporan Otomatis & Real-time',
                    'description' => 'Rekap kehadiran, keterlambatan, dan lembur tersaji otomatis secara real-time, memudahkan HR memantau kedisiplinan tanpa rekap manual.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-money',
                    'title' => 'Integrasi Payroll',
                    'description' => 'Data absensi terhubung langsung ke perhitungan gaji, memangkas waktu admin HR dalam menyiapkan slip gaji setiap bulan.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-sitemap',
                    'title' => 'Multi Cabang & Shift',
                    'description' => 'Kelola banyak cabang, shift kerja, dan pengajuan cuti/izin karyawan dalam satu dashboard terpusat.',
                    'order_index' => 4,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if ($product->faqs()->count() === 0) {
            $this->insertFor($product, 'faqs', [
                [
                    'question' => 'Apakah Absen ASA bisa dipakai untuk perusahaan dengan banyak cabang?',
                    'answer' => 'Bisa. Absen ASA mendukung pengelolaan multi-cabang dan multi-shift dari satu dashboard admin yang sama.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Apakah masih perlu mesin fingerprint?',
                    'answer' => 'Tidak. Absen ASA berjalan dari smartphone karyawan dengan validasi GPS dan selfie, sehingga perusahaan tidak perlu membeli/merawat mesin fingerprint.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Perangkat apa saja yang didukung Absen ASA?',
                    'answer' => 'Absen ASA tersedia untuk perangkat Android dan iOS, serta dapat dipantau lewat dashboard berbasis web untuk admin/HR.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Apakah data absensi karyawan aman?',
                    'answer' => 'Aman. Seluruh data disimpan terenkripsi di server dan hanya dapat diakses oleh pihak yang memiliki izin (admin/HR perusahaan terkait).',
                    'order_index' => 4,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Logo klien placeholder (gambar generik bawaan template) — ganti dengan
        // logo klien asli lewat Admin > Absen ASA > Klien.
        if ($product->clients()->count() === 0) {
            $this->seedImage('product-clients/absen-asa-1.jpg', 'images/page-1_img10.jpg');
            $this->seedImage('product-clients/absen-asa-2.jpg', 'images/page-1_img11.jpg');
            $this->seedImage('product-clients/absen-asa-3.jpg', 'images/page-1_img12.jpg');
            $this->insertFor($product, 'clients', [
                ['name' => 'Klien A', 'logo' => 'product-clients/absen-asa-1.jpg', 'order_index' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Klien B', 'logo' => 'product-clients/absen-asa-2.jpg', 'order_index' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Klien C', 'logo' => 'product-clients/absen-asa-3.jpg', 'order_index' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    private function seedKlinikAsa(): void
    {
        $product = $this->product(
            ['name' => 'Klinik ASA', 'slug' => 'klinik-asa', 'nav_icon' => 'fa-user-md',
             'nav_description' => 'Sistem manajemen klinik terintegrasi',
             'nav_description_en' => 'Integrated clinic management system',
             'order_index' => 2, 'is_active' => true],
            'Klinik ASA, Sistem Manajemen Klinik Terintegrasi untuk Pelayanan yang Lebih Cepat dan Rapi',
            'Klinik ASA menyatukan pendaftaran pasien, rekam medis elektronik, jadwal dokter, hingga kasir dan apotek dalam satu sistem — memudahkan tenaga medis dan staf klinik melayani pasien lebih cepat dan tertata.'
        );

        if ($product->benefits()->count() === 0) {
            $this->insertFor($product, 'benefits', [
                [
                    'icon' => 'fa-file-text-o',
                    'title' => 'Rekam Medis Elektronik',
                    'description' => 'Riwayat pemeriksaan dan diagnosa pasien tersimpan rapi secara digital, mudah diakses kembali oleh dokter kapan saja dibutuhkan.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-calendar-check-o',
                    'title' => 'Pendaftaran & Antrian Online',
                    'description' => 'Pasien dapat mendaftar dan mengambil nomor antrian dari HP tanpa harus datang lebih awal dan menunggu lama di klinik.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-shopping-cart',
                    'title' => 'Kasir & Apotek Terintegrasi',
                    'description' => 'Tagihan pemeriksaan, tindakan medis, dan obat dari apotek terhitung otomatis dalam satu sistem kasir, mengurangi risiko salah hitung.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'icon' => 'fa-user-md',
                    'title' => 'Jadwal Dokter Fleksibel',
                    'description' => 'Atur jadwal praktik dokter dan shift tenaga medis dengan mudah, meminimalkan bentrok jadwal antar dokter.',
                    'order_index' => 4,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if ($product->faqs()->count() === 0) {
            $this->insertFor($product, 'faqs', [
                [
                    'question' => 'Apakah Klinik ASA cocok untuk klinik kecil/praktik mandiri?',
                    'answer' => 'Cocok. Klinik ASA dapat digunakan mulai dari praktik dokter mandiri hingga klinik dengan banyak dokter dan cabang.',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Apakah Klinik ASA bisa terhubung dengan BPJS?',
                    'answer' => 'Integrasi BPJS dapat disesuaikan sesuai kebutuhan klinik — silakan diskusikan kebutuhan spesifik Anda dengan tim kami lewat WhatsApp.',
                    'order_index' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Apakah data pasien di Klinik ASA aman?',
                    'answer' => 'Aman. Data rekam medis dan data pasien disimpan terenkripsi dan hanya dapat diakses oleh tenaga medis/staf yang berwenang.',
                    'order_index' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'question' => 'Bisakah Klinik ASA dipakai untuk klinik dengan banyak cabang?',
                    'answer' => 'Bisa. Data pasien dan rekam medis dapat dikelola secara terpusat maupun per cabang sesuai kebutuhan operasional klinik Anda.',
                    'order_index' => 4,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if ($product->clients()->count() === 0) {
            $this->seedImage('product-clients/klinik-asa-1.jpg', 'images/page-1_img10.jpg');
            $this->seedImage('product-clients/klinik-asa-2.jpg', 'images/page-1_img11.jpg');
            $this->seedImage('product-clients/klinik-asa-3.jpg', 'images/page-1_img12.jpg');
            $this->insertFor($product, 'clients', [
                ['name' => 'Klien A', 'logo' => 'product-clients/klinik-asa-1.jpg', 'order_index' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Klien B', 'logo' => 'product-clients/klinik-asa-2.jpg', 'order_index' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Klien C', 'logo' => 'product-clients/klinik-asa-3.jpg', 'order_index' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Copy gambar bawaan template (public/images/...) ke storage disk
     * "public", sama seperti helper di DatabaseSeeder. Dibuat idempotent:
     * dilewati kalau file tujuan sudah ada.
     */
    private function seedImage(string $target, string $sourceRelativeToImages): void
    {
        if (Storage::disk('public')->exists($target)) {
            return;
        }

        $sourcePath = public_path($sourceRelativeToImages);

        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($target, File::get($sourcePath));
        }
    }
}
