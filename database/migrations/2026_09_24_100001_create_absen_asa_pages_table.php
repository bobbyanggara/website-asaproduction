<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absen_asa_pages', function (Blueprint $table) {
            $table->id();

            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();

            $table->string('preview_title')->nullable();
            $table->text('preview_caption')->nullable();
            $table->string('preview_media')->nullable();

            $table->string('meta_description', 500)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen_asa_pages');
    }
};
