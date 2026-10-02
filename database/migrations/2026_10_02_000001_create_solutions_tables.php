<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Halaman solusi dinamis.
 *
 * Sebelumnya 3 halaman solusi (Website, App Mobile, Sistem Kustom) masing-masing
 * punya 5 tabel sendiri (*_pages, *_benefits, *_clients, *_portfolios, *_faqs)
 * + controller + route + view sendiri, jadi menambah halaman solusi baru selalu
 * butuh edit kode. Migration ini menggantinya dengan satu set tabel generik
 * berkunci `solution_id` (pola yang sama dengan tabel `products`), sehingga
 * halaman solusi baru cukup ditambah lewat panel admin.
 *
 * Data 3 halaman lama DIPINDAHKAN otomatis dengan slug yang sama
 * (jasa-pembuatan-website, jasa-pembuatan-app-mobile, jasa-pembuatan-sistem-kustom),
 * jadi URL lama /solusi/jasa-pembuatan-* tetap jalan. Tabel lama sengaja TIDAK
 * dihapus supaya ada jalan mundur; boleh di-drop manual setelah dipastikan aman.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();

            // Identitas & tampilan di navbar / footer
            $table->string('name');
            $table->text('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('nav_icon')->nullable();            // kelas font-awesome, mis. fa-desktop
            $table->string('nav_description')->nullable();     // subtitle di dropdown navbar
            $table->text('nav_description_en')->nullable();
            $table->string('eyebrow')->nullable();             // teks kecil di atas judul hero
            $table->text('eyebrow_en')->nullable();

            // Hero
            $table->string('hero_title')->nullable();
            $table->text('hero_title_en')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('hero_subtitle_en')->nullable();
            $table->string('hero_image')->nullable();

            // Preview solusi
            $table->string('preview_title')->nullable();
            $table->text('preview_title_en')->nullable();
            $table->text('preview_caption')->nullable();
            $table->text('preview_caption_en')->nullable();
            $table->string('preview_media')->nullable();

            $table->string('meta_description', 500)->nullable();

            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('solution_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_id')->constrained('solutions')->cascadeOnDelete();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('title_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('solution_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_id')->constrained('solutions')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('logo');
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('solution_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_id')->constrained('solutions')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('title_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->string('thumbnail');                       // tampil di grid
            $table->string('image')->nullable();               // full-size, fallback ke thumbnail
            $table->string('link')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('solution_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_id')->constrained('solutions')->cascadeOnDelete();
            $table->string('question');
            $table->text('question_en')->nullable();
            $table->text('answer');
            $table->text('answer_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $this->migrateLegacy();
    }

    public function down(): void
    {
        Schema::dropIfExists('solution_faqs');
        Schema::dropIfExists('solution_portfolios');
        Schema::dropIfExists('solution_clients');
        Schema::dropIfExists('solution_benefits');
        Schema::dropIfExists('solutions');
    }

    /**
     * Buat 3 halaman solusi bawaan. Konten dari tabel lama disalin kalau ada;
     * kalau tidak (instalasi baru / kolom kosong), dipakai teks bawaan yang
     * sama dengan yang dulu tampil di halaman publik, jadi tampilan situs
     * tidak berubah sama sekali.
     */
    private function migrateLegacy(): void
    {
        $site = (Schema::hasTable('settings') ? DB::table('settings')->value('site_title') : null) ?: 'Kami';

        $legacy = [
            [
                'prefix' => 'website',
                'name' => 'Jasa Pembuatan Website',
                'name_en' => 'Website Development Services',
                'slug' => 'jasa-pembuatan-website',
                'nav_icon' => 'fa-desktop',
                'nav_description' => 'Website profesional & responsif sesuai kebutuhan bisnis Anda',
                'nav_description_en' => 'Professional, responsive websites tailored to your business',
                'hero_title' => 'Asa Production, Solusi Tepat untuk Pembuatan Website yang Cepat, Rapi, dan Profesional',
                'hero_title_en' => 'Asa Production: The Right Solution for Fast, Clean, and Professional Website Development',
                'hero_subtitle' => $site.' membantu bisnis Anda tampil profesional di internet lewat website yang cepat, aman, dan mudah dikelola sendiri. Cocok untuk company profile, toko online, landing page promosi, hingga sistem berbasis web.',
                'hero_subtitle_en' => $site.' helps your business look professional online with websites that are fast, secure, and easy to manage yourself. Ideal for company profiles, online stores, promotional landing pages, and web-based systems.',
                'meta_description' => 'Jasa pembuatan website profesional dan responsif.',
                'order_index' => 1,
            ],
            [
                'prefix' => 'app_mobile',
                'name' => 'Jasa Pembuatan App Mobile',
                'name_en' => 'Mobile App Development Services',
                'slug' => 'jasa-pembuatan-app-mobile',
                'nav_icon' => 'fa-mobile',
                'nav_description' => 'Aplikasi Android & iOS yang disesuaikan dengan kebutuhan bisnis',
                'nav_description_en' => 'Android & iOS apps built around your business needs',
                'hero_title' => 'Asa Production, Solusi Tepat untuk Pengembangan Aplikasi Android & iOS yang Mudah dan Efektif',
                'hero_title_en' => 'Asa Production: The Right Solution for Easy and Effective Android & iOS App Development',
                'hero_subtitle' => $site.' siap membantu Anda mengembangkan aplikasi Android & iOS dengan mudah dan efektif. Dengan tim ahli dan teknologi terkini, kami menghadirkan solusi aplikasi yang responsif, user-friendly, dan sesuai dengan kebutuhan bisnis Anda.',
                'hero_subtitle_en' => $site.' is ready to help you develop Android & iOS apps easily and effectively. With an expert team and the latest technology, we deliver responsive, user-friendly app solutions tailored to your business needs.',
                'meta_description' => 'Jasa pembuatan aplikasi mobile Android, iOS, dan cross-platform.',
                'order_index' => 2,
            ],
            [
                'prefix' => 'custom_system',
                'name' => 'Jasa Pembuatan Sistem Kustom',
                'name_en' => 'Custom System Development Services',
                'slug' => 'jasa-pembuatan-sistem-kustom',
                'nav_icon' => 'fa-cogs',
                'nav_description' => 'Sistem/software kustom untuk mengotomasi proses bisnis Anda',
                'nav_description_en' => 'Custom software to automate your business processes',
                'hero_title' => 'Asa Production, Solusi Tepat untuk Pengembangan Sistem Kustom Sesuai Kebutuhan Bisnis Anda',
                'hero_title_en' => 'Asa Production: The Right Solution for Custom Systems Built Around Your Business Needs',
                'hero_subtitle' => $site.' merancang dan membangun sistem/software kustom untuk mengotomasi proses bisnis Anda — mulai dari sistem absensi, manajemen inventori, hingga aplikasi internal khusus yang dirancang sesuai alur kerja perusahaan Anda.',
                'hero_subtitle_en' => $site." designs and builds custom systems and software to automate your business processes — from attendance systems and inventory management to tailor-made internal apps designed around your company's workflow.",
                'meta_description' => 'Jasa pembuatan sistem/software kustom sesuai kebutuhan bisnis.',
                'order_index' => 3,
            ],
        ];

        foreach ($legacy as $item) {
            $pagesTable = $item['prefix'].'_pages';
            $page = Schema::hasTable($pagesTable) ? DB::table($pagesTable)->where('id', 1)->first() : null;

            // Isi dari tabel lama menang kalau tidak kosong; kalau kosong pakai teks bawaan.
            // Kalau admin sudah mengubah teks Indonesia, versi Inggris bawaan tidak ikut
            // dipakai (akan diterjemahkan ulang otomatis saat disimpan dari admin).
            $heroTitle = filled($page->hero_title ?? null) ? $page->hero_title : $item['hero_title'];
            $heroTitleEn = filled($page->hero_title ?? null)
                ? ($page->hero_title_en ?? null)
                : $item['hero_title_en'];
            $heroSubtitle = filled($page->hero_subtitle ?? null) ? $page->hero_subtitle : $item['hero_subtitle'];
            $heroSubtitleEn = filled($page->hero_subtitle ?? null)
                ? ($page->hero_subtitle_en ?? null)
                : $item['hero_subtitle_en'];

            $solutionId = DB::table('solutions')->insertGetId([
                'name' => $item['name'],
                'name_en' => $item['name_en'],
                'slug' => $item['slug'],
                'nav_icon' => $item['nav_icon'],
                'nav_description' => $item['nav_description'],
                'nav_description_en' => $item['nav_description_en'],
                'eyebrow' => null,
                'hero_title' => $heroTitle,
                'hero_title_en' => $heroTitleEn,
                'hero_subtitle' => $heroSubtitle,
                'hero_subtitle_en' => $heroSubtitleEn,
                'hero_image' => $page->hero_image ?? null,
                'preview_title' => $page->preview_title ?? null,
                'preview_title_en' => $page->preview_title_en ?? null,
                'preview_caption' => $page->preview_caption ?? null,
                'preview_caption_en' => $page->preview_caption_en ?? null,
                'preview_media' => $page->preview_media ?? null,
                'meta_description' => filled($page->meta_description ?? null) ? $page->meta_description : $item['meta_description'],
                'order_index' => $item['order_index'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->copyRows($item['prefix'].'_benefits', 'solution_benefits', $solutionId,
                ['icon', 'title', 'title_en', 'description', 'description_en', 'order_index', 'is_active']);
            $this->copyRows($item['prefix'].'_clients', 'solution_clients', $solutionId,
                ['name', 'logo', 'order_index', 'is_active']);
            $this->copyRows($item['prefix'].'_portfolios', 'solution_portfolios', $solutionId,
                ['title', 'title_en', 'description', 'description_en', 'thumbnail', 'image', 'link', 'order_index', 'is_active']);
            $this->copyRows($item['prefix'].'_faqs', 'solution_faqs', $solutionId,
                ['question', 'question_en', 'answer', 'answer_en', 'order_index', 'is_active']);
        }
    }

    /** Salin baris dari tabel lama; kolom yang tidak ada di tabel lama diisi null. */
    private function copyRows(string $from, string $to, int $solutionId, array $columns): void
    {
        if (! Schema::hasTable($from)) {
            return;
        }

        DB::table($from)->orderBy('id')->get()->each(function ($row) use ($to, $solutionId, $columns) {
            $data = ['solution_id' => $solutionId, 'created_at' => now(), 'updated_at' => now()];

            foreach ($columns as $column) {
                $data[$column] = $row->{$column} ?? null;
            }

            // Kolom is_active/order_index punya default; jangan kirim null ke kolom NOT NULL.
            $data['is_active'] = $data['is_active'] ?? true;
            $data['order_index'] = $data['order_index'] ?? 0;

            DB::table($to)->insert($data);
        });
    }
};
