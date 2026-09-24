<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Halaman produk dinamis.
 *
 * Sebelumnya tiap produk (Absen ASA, Klinik ASA) punya 4 tabel sendiri
 * (*_pages, *_benefits, *_clients, *_faqs) + controller + route + view sendiri,
 * jadi menambah produk baru selalu butuh edit kode. Migration ini menggantinya
 * dengan satu set tabel generik berkunci `product_id`, sehingga produk baru
 * cukup ditambah lewat panel admin.
 *
 * Data produk lama DIPINDAHKAN otomatis (slug tetap absen-asa & klinik-asa,
 * jadi URL /produk/absen-asa dan /produk/klinik-asa tetap jalan). Tabel lama
 * sengaja TIDAK dihapus di sini supaya ada jalan mundur; boleh di-drop manual
 * setelah dipastikan semuanya aman.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Identitas & tampilan di navbar
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('nav_icon')->nullable();            // kelas font-awesome, mis. fa-clock-o
            $table->string('nav_description')->nullable();     // subtitle di dropdown navbar
            $table->string('nav_description_en')->nullable();  // subtitle bahasa Inggris (opsional)
            $table->string('eyebrow')->nullable();             // teks kecil di atas judul hero

            // Hero
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();

            // Preview produk
            $table->string('preview_title')->nullable();
            $table->text('preview_caption')->nullable();
            $table->string('preview_media')->nullable();

            $table->string('meta_description', 500)->nullable();

            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('logo');
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $this->migrateLegacy();
    }

    public function down(): void
    {
        Schema::dropIfExists('product_faqs');
        Schema::dropIfExists('product_clients');
        Schema::dropIfExists('product_benefits');
        Schema::dropIfExists('products');
    }

    /**
     * Pindahkan data dari tabel lama ke tabel baru. Setiap langkah dicek
     * dulu (tabel lama ada atau tidak) supaya migration tetap aman di
     * instalasi baru yang tidak pernah punya tabel lama.
     */
    private function migrateLegacy(): void
    {
        $legacy = [
            [
                'prefix' => 'absen_asa',
                'name' => 'Absen ASA',
                'slug' => 'absen-asa',
                'nav_icon' => 'fa-clock-o',
                'nav_description' => 'Aplikasi absensi karyawan berbasis GPS & selfie',
                'nav_description_en' => 'Employee attendance app with GPS & selfie verification',
                'order_index' => 1,
            ],
            [
                'prefix' => 'klinik_asa',
                'name' => 'Klinik ASA',
                'slug' => 'klinik-asa',
                'nav_icon' => 'fa-user-md',
                'nav_description' => 'Sistem manajemen klinik terintegrasi',
                'nav_description_en' => 'Integrated clinic management system',
                'order_index' => 2,
            ],
        ];

        foreach ($legacy as $item) {
            $pagesTable = $item['prefix'].'_pages';

            // Lewati kalau produk ini memang tidak pernah ada di database lama.
            if (! Schema::hasTable($pagesTable)) {
                continue;
            }

            $page = DB::table($pagesTable)->where('id', 1)->first();

            $productId = DB::table('products')->insertGetId([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'nav_icon' => $item['nav_icon'],
                'nav_description' => $item['nav_description'],
                'nav_description_en' => $item['nav_description_en'],
                'hero_title' => $page->hero_title ?? null,
                'hero_subtitle' => $page->hero_subtitle ?? null,
                'hero_image' => $page->hero_image ?? null,
                'preview_title' => $page->preview_title ?? null,
                'preview_caption' => $page->preview_caption ?? null,
                'preview_media' => $page->preview_media ?? null,
                'meta_description' => $page->meta_description ?? null,
                'order_index' => $item['order_index'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->copyRows($item['prefix'].'_benefits', 'product_benefits', $productId,
                ['icon', 'title', 'description', 'order_index', 'is_active']);
            $this->copyRows($item['prefix'].'_clients', 'product_clients', $productId,
                ['name', 'logo', 'order_index', 'is_active']);
            $this->copyRows($item['prefix'].'_faqs', 'product_faqs', $productId,
                ['question', 'answer', 'order_index', 'is_active']);
        }
    }

    private function copyRows(string $from, string $to, int $productId, array $columns): void
    {
        if (! Schema::hasTable($from)) {
            return;
        }

        DB::table($from)->orderBy('id')->get()->each(function ($row) use ($to, $productId, $columns) {
            $data = ['product_id' => $productId, 'created_at' => now(), 'updated_at' => now()];

            foreach ($columns as $column) {
                $data[$column] = $row->{$column} ?? null;
            }

            DB::table($to)->insert($data);
        });
    }
};
