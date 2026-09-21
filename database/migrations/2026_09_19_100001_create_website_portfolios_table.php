<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
      Portofolio khusus halaman "Jasa Pembuatan Website" — sebelumnya section
      "Temukan inspirasimu" di halaman ini mengambil dari tabel global
      `portfolios` yang difilter pakai string kategori ("Jasa Pembuatan
      Website"), gampang typo dan nggak ada menu sendiri di sidebar admin.
      Sekarang dipisah jadi tabel sendiri (pola sama dengan
      website_benefits / website_clients), supaya admin bisa kelola dari
      Admin > Jasa Website > Portfolio tanpa nyenggol data portofolio
      global di halaman /portfolio maupun punya App Mobile/Sistem Kustom.
    */
    public function up(): void
    {
        Schema::create('website_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail'); // tampil di grid
            $table->string('image')->nullable(); // full-size, fallback ke thumbnail
            $table->string('link')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_portfolios');
    }
};
