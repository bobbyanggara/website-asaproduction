<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin user
        User::firstOrCreate(
            ['email' => 'admin@asaproduction.test'],
            [
                'name' => 'Admin Asa Production',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Site settings (isi awal, silakan diedit lewat dashboard)
        Setting::firstOrCreate(['id' => 1], [
            'site_title' => 'Asa Production',
            'brand_slogan' => 'IT Solution',
            'hero_title' => 'Professional web performance',
            'hero_subtitle' => 'Сreating something special for each customer',
            'about_title' => 'Tim kreatif kami terdiri dari para ahli di semua bidang web design',
            'about_description' => 'Asa Production membantu bisnis Anda tampil profesional secara online, mulai dari desain, pengembangan, hingga optimasi performa website.',
            'services_title' => 'Solusi IT Terbaik untuk Bisnis Anda',
            'services_description' => 'Kami membangun website, sistem, dan aplikasi yang cepat, aman, dan mudah dikelola.',
            'whatsapp_number' => '62812xxxxxxx',
            'whatsapp_message' => 'Halo Asa Production, saya ingin bertanya tentang layanan IT Solution',
            'subscribe_title' => 'Subscribe',
            'footer_title' => 'Professional web performance',
            'footer_subtitle' => 'Сreating something special for each customer',
            'footer_cta_text' => 'Get Started Now!',
            'footer_address' => 'Sidoarjo, Jawa Timur, Indonesia',
            'footer_email' => 'halo@asaproduction.test',
            'social_facebook' => 'https://facebook.com/asaproduction',
            'social_instagram' => 'https://instagram.com/asaproduction',
            'social_tiktok' => 'https://tiktok.com/@asaproduction',
            'social_linkedin' => 'https://linkedin.com/company/asaproduction',
            'social_youtube' => 'https://youtube.com/@asaproduction',
            'social_x' => 'https://x.com/asaproduction',
        ]);

        // Backfill kolom footer/sosmed di atas untuk instalasi lama yang baris
        // settings-nya sudah ada duluan (firstOrCreate di atas tidak menimpanya).
        // Hanya isi kolom yang masih kosong, tidak menimpa yang sudah diedit admin.
        $setting = Setting::current();
        $setting->fill([
            'footer_address' => $setting->footer_address ?: 'Sidoarjo, Jawa Timur, Indonesia',
            'footer_email' => $setting->footer_email ?: 'halo@asaproduction.test',
            'social_facebook' => $setting->social_facebook ?: 'https://facebook.com/asaproduction',
            'social_instagram' => $setting->social_instagram ?: 'https://instagram.com/asaproduction',
            'social_tiktok' => $setting->social_tiktok ?: 'https://tiktok.com/@asaproduction',
            'social_linkedin' => $setting->social_linkedin ?: 'https://linkedin.com/company/asaproduction',
            'social_youtube' => $setting->social_youtube ?: 'https://youtube.com/@asaproduction',
            'social_x' => $setting->social_x ?: 'https://x.com/asaproduction',
        ])->save();

        // 3. Seed sample images from the static template into storage/app/public
        $this->seedImage('team/1.jpg', 'images/page-1_img01.jpg');
        $this->seedImage('team/2.jpg', 'images/page-1_img02.jpg');
        $this->seedImage('team/3.jpg', 'images/page-1_img03.jpg');

        $this->seedImage('portfolio/1_thumb.jpg', 'images/page-1_img04.jpg');
        $this->seedImage('portfolio/1_full.jpg', 'images/page-1_img04_original.jpg');
        $this->seedImage('portfolio/2_thumb.jpg', 'images/page-1_img05.jpg');
        $this->seedImage('portfolio/2_full.jpg', 'images/page-1_img05_original.jpg');
        $this->seedImage('portfolio/3_thumb.jpg', 'images/page-1_img06.jpg');
        $this->seedImage('portfolio/3_full.jpg', 'images/page-1_img06_original.jpg');
        $this->seedImage('portfolio/4_thumb.jpg', 'images/page-1_img07.jpg');
        $this->seedImage('portfolio/4_full.jpg', 'images/page-1_img07_original.jpg');
        $this->seedImage('portfolio/5_thumb.jpg', 'images/page-1_img08.jpg');
        $this->seedImage('portfolio/5_full.jpg', 'images/page-1_img08_original.jpg');

        $this->seedImage('testimonials/1.jpg', 'images/page-1_img13.jpg');
        $this->seedImage('testimonials/2.jpg', 'images/page-1_img09.jpg');

        $this->seedImage('partners/1.jpg', 'images/page-1_img10.jpg');
        $this->seedImage('partners/2.jpg', 'images/page-1_img11.jpg');
        $this->seedImage('partners/3.jpg', 'images/page-1_img12.jpg');

        // 4. Team members
        $team = [
            ['name' => 'Sam Kromstain', 'role' => 'Lead Developer', 'photo' => 'team/1.jpg'],
            ['name' => 'Alan Smith', 'role' => 'UI/UX Designer', 'photo' => 'team/2.jpg'],
            ['name' => 'John Franklin', 'role' => 'Project Manager', 'photo' => 'team/3.jpg'],
        ];
        foreach ($team as $i => $t) {
            TeamMember::firstOrCreate(['name' => $t['name']], [
                'role' => $t['role'],
                'description' => 'Berpengalaman membantu klien membangun solusi digital yang sesuai kebutuhan bisnis.',
                'photo' => $t['photo'],
                'order_index' => $i,
            ]);
        }

        // 5. Services (fitur "WE ARE ...")
        $services = [
            ['title' => 'WE ARE CREATIVE', 'icon' => 'flaticon-toilets1', 'description' => 'Solusi desain dan pengembangan yang disesuaikan dengan identitas bisnis Anda.'],
            ['title' => 'WE ARE MODERN', 'icon' => 'flaticon-coffee69', 'description' => 'Menggunakan teknologi terbaru agar website Anda cepat dan responsif.'],
            ['title' => 'WE ARE EXPERTS', 'icon' => 'flaticon-hotel70', 'description' => 'Tim berpengalaman siap membantu dari perencanaan hingga maintenance.'],
        ];
        foreach ($services as $i => $s) {
            Service::firstOrCreate(['title' => $s['title']], [
                'icon' => $s['icon'],
                'description' => $s['description'],
                'order_index' => $i,
            ]);
        }

        // 6. Portfolio
        for ($i = 1; $i <= 5; $i++) {
            Portfolio::firstOrCreate(['thumbnail' => "portfolio/{$i}_thumb.jpg"], [
                'title' => "Project {$i}",
                'image' => "portfolio/{$i}_full.jpg",
                'order_index' => $i - 1,
            ]);
        }

        // 7. Testimonials
        $testimonials = [
            ['name' => 'Michael Freeman', 'company' => 'Client Company', 'title' => 'CEO', 'photo' => 'testimonials/1.jpg'],
            ['name' => 'Sarah Johnson', 'company' => 'Bright Marketing', 'title' => 'Marketing Manager', 'photo' => 'testimonials/2.jpg'],
        ];
        foreach ($testimonials as $i => $t) {
            Testimonial::firstOrCreate(['name' => $t['name']], [
                'company' => $t['company'],
                'title' => $t['title'],
                'message' => 'Asa Production sangat membantu bisnis kami tampil lebih profesional secara online. Prosesnya cepat dan komunikatif.',
                'photo' => $t['photo'],
                'order_index' => $i,
            ]);
        }

        // 8. Partners
        for ($i = 1; $i <= 3; $i++) {
            Partner::firstOrCreate(['logo' => "partners/{$i}.jpg"], [
                'name' => "Partner {$i}",
                'order_index' => $i - 1,
            ]);
        }

        // 9. FAQ
        $faqs = [
            [
                'question' => 'Layanan apa saja yang ditawarkan Asa Production?',
                'answer' => 'Kami menyediakan jasa pembuatan website, aplikasi web, sistem custom sesuai kebutuhan bisnis, hingga optimasi performa dan tampilan website yang sudah ada.',
            ],
            [
                'question' => 'Berapa lama waktu pengerjaan website atau aplikasi?',
                'answer' => 'Untuk website profil sederhana biasanya selesai dalam 1-2 minggu. Untuk sistem atau aplikasi custom dengan fitur lebih kompleks, waktu pengerjaan sekitar 3-8 minggu tergantung kebutuhan.',
            ],
            [
                'question' => 'Apakah bisa custom sesuai kebutuhan bisnis saya?',
                'answer' => 'Bisa. Setiap proyek kami sesuaikan dengan fitur, tampilan, dan alur kerja bisnis Anda, bukan template yang dipaksakan sama untuk semua klien.',
            ],
            [
                'question' => 'Apakah ada garansi setelah proyek selesai?',
                'answer' => 'Ya, kami memberikan garansi perbaikan bug atau kendala teknis dalam periode tertentu setelah serah terima, sesuai kesepakatan di awal proyek.',
            ],
            [
                'question' => 'Apakah saya bisa mengelola website sendiri setelah selesai dibuat?',
                'answer' => 'Tentu. Kami menyediakan panduan penggunaan dan pendampingan singkat agar Anda bisa mengelola konten website sendiri lewat halaman admin, tanpa harus paham coding.',
            ],
            [
                'question' => 'Apakah Asa Production menyediakan dukungan setelah website live?',
                'answer' => 'Ya, tim kami siap dihubungi via WhatsApp untuk bantuan teknis maupun konsultasi pengembangan lanjutan setelah website Anda berjalan.',
            ],
            [
                'question' => 'Berapa biaya pembuatan website atau aplikasi di Asa Production?',
                'answer' => 'Biaya tergantung kompleksitas fitur dan skala proyek. Silakan hubungi kami via WhatsApp untuk mendapatkan penawaran yang sesuai dengan kebutuhan bisnis Anda.',
            ],
            [
                'question' => 'Apakah Asa Production membantu optimasi SEO?',
                'answer' => 'Ya, setiap website yang kami bangun sudah memperhatikan dasar-dasar SEO seperti kecepatan loading, struktur heading, dan meta tag agar lebih mudah ditemukan di mesin pencari.',
            ],
            [
                'question' => 'Bagaimana cara mulai konsultasi dengan tim Asa Production?',
                'answer' => 'Anda bisa langsung menekan tombol WhatsApp di website ini untuk konsultasi gratis mengenai kebutuhan website atau aplikasi bisnis Anda.',
            ],
        ];
        foreach ($faqs as $i => $f) {
            Faq::firstOrCreate(['question' => $f['question']], [
                'answer' => $f['answer'],
                'order_index' => $i,
            ]);
        }

        // 10. Blog (3 artikel contoh)
        $this->call(PostSeeder::class);

        // 11. Konten placeholder untuk dua produk bawaan (Absen ASA & Klinik ASA,
        //     tabel products): hero, Keunggulan, FAQ, dan logo klien contoh —
        //     silakan diedit lewat Admin > Semua Produk. Produk lain ditambah
        //     langsung dari panel admin, tanpa seeder.
        $this->call(ProdukPagesContentSeeder::class);
    }

    /**
     * Copy an image that ships inside public/assets/images (from the original
     * static template) into the public storage disk, so admin CRUD & seeded
     * records point to real files out of the box.
     */
    private function seedImage(string $target, string $sourceRelativeToAssets): void
    {
        if (Storage::disk('public')->exists($target)) {
            return;
        }

        $sourcePath = public_path('assets/'.$sourceRelativeToAssets);

        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($target, File::get($sourcePath));
        }
    }
}
