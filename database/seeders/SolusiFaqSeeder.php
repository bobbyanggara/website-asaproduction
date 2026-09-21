<?php

namespace Database\Seeders;

use App\Models\AppMobileFaq;
use App\Models\CustomSystemFaq;
use App\Models\WebsiteFaq;
use Illuminate\Database\Seeder;

/**
 * Isi FAQ placeholder untuk ketiga halaman /solusi/* (App Mobile, Website,
 * Sistem Kustom) — brand "Asa Production". Isinya disesuaikan konteks
 * masing-masing layanan (bukan generic/copy-paste), jadi bisa langsung
 * dipakai atau diedit lagi lewat Admin > Halaman Solusi > [layanan] > FAQ.
 *
 * Aman dijalankan berkali-kali: hanya mengisi kalau tabelnya masih kosong.
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=Database\\Seeders\\SolusiFaqSeeder
 */
class SolusiFaqSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedWebsite();
        $this->seedAppMobile();
        $this->seedCustomSystem();
    }

    private function seedWebsite(): void
    {
        if (WebsiteFaq::count() > 0) {
            return;
        }

        $this->insert(WebsiteFaq::class, [
            [
                'Berapa lama waktu pengerjaan website?',
                'Tergantung kompleksitas kebutuhan. Untuk company profile atau landing page biasanya 1-2 minggu, sedangkan website dengan fitur khusus (toko online, sistem member, dll) bisa 3-6 minggu. Estimasi pasti akan diberikan setelah diskusi kebutuhan awal.',
            ],
            [
                'Apakah website yang dibuat sudah responsif di HP?',
                'Ya, semua website yang kami buat otomatis menyesuaikan tampilan di HP, tablet, maupun desktop (responsive design), tanpa biaya tambahan.',
            ],
            [
                'Apakah saya bisa mengubah isi website sendiri setelah jadi?',
                'Bisa. Website dilengkapi panel admin sederhana untuk mengubah teks, foto, dan artikel kapan saja tanpa perlu bantuan developer. Kami juga akan memberi panduan penggunaannya.',
            ],
            [
                'Domain dan hosting apakah sudah termasuk dalam paket?',
                'Tergantung paket yang dipilih. Sebagian paket sudah termasuk domain & hosting tahun pertama, sebagian lagi khusus jasa pembuatan saja. Detail cakupan setiap paket bisa didiskusikan langsung dengan tim kami.',
            ],
            [
                'Apakah website sudah SEO friendly?',
                'Ya, struktur dasar website (judul halaman, meta description, kecepatan loading, dan markup HTML) sudah dibangun mengikuti kaidah SEO sejak awal, agar lebih mudah ditemukan lewat pencarian Google.',
            ],
            [
                'Bagaimana jika saya butuh revisi desain?',
                'Setiap paket sudah termasuk jatah revisi pada tahap desain sebelum website di-develop lebih lanjut. Ketentuan jumlah revisi mengikuti paket yang dipilih dan bisa didiskusikan di awal.',
            ],
        ]);
    }

    private function seedAppMobile(): void
    {
        if (AppMobileFaq::count() > 0) {
            return;
        }

        $this->insert(AppMobileFaq::class, [
            [
                'Aplikasi bisa dibuat untuk Android dan iOS sekaligus?',
                'Bisa. Kami mengembangkan aplikasi cross-platform (Android & iOS dari satu basis kode) maupun native terpisah, tergantung kebutuhan dan anggaran Anda.',
            ],
            [
                'Berapa lama proses pengembangan aplikasi mobile?',
                'Untuk aplikasi dengan fitur standar biasanya 4-8 minggu, sedangkan aplikasi dengan fitur kompleks (integrasi API pihak ketiga, sistem real-time, dll) bisa lebih lama. Timeline detail diberikan setelah analisis kebutuhan.',
            ],
            [
                'Apakah aplikasi akan dibantu publish ke Play Store / App Store?',
                'Ya, kami membantu proses submit aplikasi hingga tayang di Google Play Store maupun Apple App Store, termasuk penyesuaian dengan ketentuan masing-masing platform.',
            ],
            [
                'Setelah aplikasi rilis, apakah masih ada dukungan lanjutan?',
                'Ada. Kami menyediakan dukungan pasca-rilis untuk perbaikan bug, penyesuaian kecil, dan konsultasi pengembangan fitur baru sesuai ketentuan paket yang dipilih.',
            ],
            [
                'Apakah saya perlu punya server sendiri?',
                'Tidak selalu. Untuk aplikasi yang butuh backend, kami bisa membantu menyiapkan server/hosting yang sesuai, atau menyesuaikan dengan infrastruktur yang sudah Anda miliki.',
            ],
            [
                'Metodologi pengembangannya seperti apa?',
                'Kami menggunakan pendekatan agile, sehingga progres dikerjakan bertahap dan Anda bisa memberi masukan di setiap tahap, bukan menunggu produk jadi 100% baru bisa dievaluasi.',
            ],
        ]);
    }

    private function seedCustomSystem(): void
    {
        if (CustomSystemFaq::count() > 0) {
            return;
        }

        $this->insert(CustomSystemFaq::class, [
            [
                'Apa bedanya sistem kustom dengan software siap pakai?',
                'Sistem kustom dirancang khusus mengikuti alur kerja dan kebutuhan bisnis Anda, bukan template umum. Fitur, tampilan, dan proses di dalamnya disesuaikan agar benar-benar pas dengan cara tim Anda bekerja.',
            ],
            [
                'Jenis sistem apa saja yang bisa dikembangkan?',
                'Beragam, mulai dari sistem absensi, manajemen inventori, sistem akademik, aplikasi internal perusahaan, hingga sistem yang mengintegrasikan beberapa proses bisnis sekaligus.',
            ],
            [
                'Berapa lama proses pengembangan sistem kustom?',
                'Tergantung kompleksitas modul dan proses bisnis yang perlu diakomodasi. Setelah tahap analisis kebutuhan, kami akan memberikan estimasi timeline dan tahapan pengerjaan yang jelas.',
            ],
            [
                'Apakah sistem bisa dikembangkan lagi di kemudian hari?',
                'Bisa. Sistem dirancang modular agar mudah ditambah fitur baru atau dihubungkan dengan sistem lain seiring bisnis Anda berkembang, tanpa harus membangun ulang dari awal.',
            ],
            [
                'Apakah ada pelatihan untuk tim kami setelah sistem selesai?',
                'Ada. Kami memberikan pendampingan setelah sistem live, termasuk pelatihan penggunaan untuk tim Anda serta dukungan perbaikan dan penyesuaian kebutuhan baru.',
            ],
            [
                'Bagaimana proses analisis kebutuhan di awal?',
                'Kami akan berdiskusi langsung dengan Anda untuk memahami alur kerja, kendala yang ada, dan tujuan bisnis, sebelum merancang spesifikasi sistem yang akan dibangun.',
            ],
        ]);
    }

    /**
     * @param  class-string  $model
     * @param  array<int, array{0: string, 1: string}>  $items  [question, answer]
     */
    private function insert(string $model, array $items): void
    {
        $rows = [];

        foreach ($items as $index => [$question, $answer]) {
            $rows[] = [
                'question' => $question,
                'answer' => $answer,
                'order_index' => $index + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $model::insert($rows);
    }
}
