<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Dokumen legal per aplikasi.
 *
 * - legal_apps  : daftar aplikasi (mis. "Kalkulator Shopee Seller").
 * - legal_pages : kolom legal_app_id baru. NULL = halaman umum situs Asa Production
 *                 (/ketentuan-layanan & /kebijakan-privasi, tetap seperti sebelumnya).
 *                 Terisi = dokumen milik satu aplikasi (terms / privacy per aplikasi).
 * Unique "slug" diganti jadi unique (legal_app_id, slug) supaya tiap aplikasi
 * boleh punya "terms" dan "privacy" sendiri.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();        // dipakai di URL publik /{slug}/ketentuan-layanan
            $table->string('description', 300)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('legal_pages', function (Blueprint $table) {
            $table->foreignId('legal_app_id')->nullable()->after('id')
                ->constrained('legal_apps')->cascadeOnDelete();
            $table->dropUnique(['slug']);
            $table->unique(['legal_app_id', 'slug']);
        });
    }

    public function down(): void
    {
        // Dokumen milik aplikasi dihapus dulu agar unique(slug) bisa dipasang lagi.
        DB::table('legal_pages')->whereNotNull('legal_app_id')->delete();

        Schema::table('legal_pages', function (Blueprint $table) {
            $table->dropUnique(['legal_app_id', 'slug']);
            $table->dropConstrainedForeignId('legal_app_id');
            $table->unique('slug');
        });

        Schema::dropIfExists('legal_apps');
    }
};
