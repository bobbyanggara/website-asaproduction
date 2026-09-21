<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Sama seperti website_portfolios, versi khusus halaman "Jasa Pembuatan
    // Sistem Kustom" — lihat komentar di migration website_portfolios.
    public function up(): void
    {
        Schema::create('custom_system_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_system_portfolios');
    }
};
