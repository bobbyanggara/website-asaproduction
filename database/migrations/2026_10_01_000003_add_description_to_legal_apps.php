<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pengaman: pastikan kolom "description" ada di legal_apps. Kalau tabel dibuat
 * dari versi migrasi sebelumnya yang tanpa kolom ini, kolom ditambahkan di sini;
 * kalau sudah ada, tidak melakukan apa-apa.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('legal_apps', 'description')) {
            Schema::table('legal_apps', function (Blueprint $table) {
                $table->string('description', 300)->nullable()->after('slug');
            });
        }
    }

    public function down(): void
    {
        // Sengaja dikosongkan: kolom ini juga dibuat oleh migrasi create_legal_apps.
    }
};
