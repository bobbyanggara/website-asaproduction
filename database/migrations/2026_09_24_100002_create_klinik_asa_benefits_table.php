<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klinik_asa_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klinik_asa_benefits');
    }
};
