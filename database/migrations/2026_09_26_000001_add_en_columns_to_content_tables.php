<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah kolom versi Inggris ("<field>_en") untuk semua konten yang tampil di
 * situs publik dan bisa diedit dari Admin. Kolom ini diisi otomatis saat admin
 * menyimpan (lihat App\Models\Concerns\AutoTranslates), jadi admin cukup menulis
 * dalam Bahasa Indonesia.
 *
 * Aman dijalankan ulang: kolom yang sudah ada dilewati. Semua kolom bertipe TEXT
 * karena hasil terjemahan bisa lebih panjang dari teks aslinya.
 */
return new class extends Migration
{
    /** tabel => [kolom Indonesia, ...] */
    private function map(): array
    {
        return [
            'settings' => ['promo_text'],
            'team_members' => ['role', 'description'],
            'services' => ['title', 'description'],
            'portfolios' => ['title', 'category', 'description'],
            'testimonials' => ['title', 'message'],
            'faqs' => ['question', 'answer'],
            'posts' => ['title', 'excerpt', 'content'],

            // Halaman solusi (Website / App Mobile / Sistem Kustom)
            'website_pages' => ['hero_title', 'hero_subtitle', 'preview_title', 'preview_caption'],
            'app_mobile_pages' => ['hero_title', 'hero_subtitle', 'preview_title', 'preview_caption'],
            'custom_system_pages' => ['hero_title', 'hero_subtitle', 'preview_title', 'preview_caption'],
            'website_benefits' => ['title', 'description'],
            'app_mobile_benefits' => ['title', 'description'],
            'custom_system_benefits' => ['title', 'description'],
            'website_faqs' => ['question', 'answer'],
            'app_mobile_faqs' => ['question', 'answer'],
            'custom_system_faqs' => ['question', 'answer'],
            'website_portfolios' => ['title', 'description'],
            'app_mobile_portfolios' => ['title', 'description'],
            'custom_system_portfolios' => ['title', 'description'],

            // Halaman produk (dinamis). products.nav_description_en sudah ada dari migration produk.
            'products' => ['eyebrow', 'hero_title', 'hero_subtitle', 'preview_title', 'preview_caption', 'nav_description'],
            'product_benefits' => ['title', 'description'],
            'product_faqs' => ['question', 'answer'],
        ];
    }

    public function up(): void
    {
        foreach ($this->map() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                $en = $column.'_en';

                if (! Schema::hasColumn($table, $column) || Schema::hasColumn($table, $en)) {
                    continue;
                }

                Schema::table($table, function (Blueprint $blueprint) use ($en, $column) {
                    $blueprint->text($en)->nullable()->after($column);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->map() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                $en = $column.'_en';

                // products.nav_description_en dibuat migration lain; jangan dihapus di sini.
                if ($table === 'products' && $column === 'nav_description') {
                    continue;
                }

                if (Schema::hasColumn($table, $en)) {
                    Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropColumn($en));
                }
            }
        }
    }
};
