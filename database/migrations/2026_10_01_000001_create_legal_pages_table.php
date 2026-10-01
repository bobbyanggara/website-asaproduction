<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Halaman legal dinamis (Ketentuan Layanan & Kebijakan Privasi).
 *
 * Satu baris per halaman, dibedakan lewat kolom "slug" (terms / privacy).
 * Isi awal dibuat otomatis dari App\Support\LegalDefaults saat halaman
 * pertama kali dibuka atau diedit, jadi tidak perlu seeder.
 * Kolom "*_en" adalah versi Inggris (diisi otomatis oleh trait AutoTranslates
 * saat admin menyimpan, sama seperti konten lain di situs ini).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();           // terms | privacy
            $table->string('title');
            $table->text('title_en')->nullable();
            $table->text('excerpt')->nullable();        // ringkasan di bawah judul
            $table->text('excerpt_en')->nullable();
            $table->longText('content')->nullable();    // HTML dari rich text editor
            $table->longText('content_en')->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->date('effective_date')->nullable(); // "Berlaku sejak / terakhir diperbarui"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_pages');
    }
};
