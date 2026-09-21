<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan field-field yang dibutuhkan supaya section Hero & "Preview
 * Solusi" di 3 halaman /solusi/* (App Mobile, Website, Custom System) bisa
 * diisi/diubah dari Admin — sebelumnya dihapus total waktu section publiknya
 * dikosongkan.
 *
 * Dibuat dengan Schema::hasColumn(...) di setiap kolom supaya migration ini
 * aman dijalankan meskipun sebagian kolom (mis. meta_description) sudah ada
 * lebih dulu di tabel.
 */
return new class extends Migration
{
    private array $tables = ['app_mobile_pages', 'website_pages', 'custom_system_pages'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (! Schema::hasColumn($table, 'hero_title')) {
                    $blueprint->string('hero_title')->nullable()->after('id');
                }
                if (! Schema::hasColumn($table, 'hero_subtitle')) {
                    $blueprint->text('hero_subtitle')->nullable()->after('hero_title');
                }
                if (! Schema::hasColumn($table, 'hero_image')) {
                    $blueprint->string('hero_image')->nullable()->after('hero_subtitle');
                }
                if (! Schema::hasColumn($table, 'preview_title')) {
                    $blueprint->string('preview_title')->nullable()->after('hero_image');
                }
                if (! Schema::hasColumn($table, 'preview_caption')) {
                    $blueprint->text('preview_caption')->nullable()->after('preview_title');
                }
                if (! Schema::hasColumn($table, 'preview_media')) {
                    // Path hasil upload (gambar/gif/video mp4) di storage/app/public/...
                    $blueprint->string('preview_media')->nullable()->after('preview_caption');
                }
                if (! Schema::hasColumn($table, 'meta_description')) {
                    $blueprint->string('meta_description')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                foreach (['hero_title', 'hero_subtitle', 'hero_image', 'preview_title', 'preview_caption', 'preview_media'] as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        $blueprint->dropColumn($column);
                    }
                }
            });
        }
    }
};
