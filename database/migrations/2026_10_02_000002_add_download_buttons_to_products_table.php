<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tombol "Download for Android" / "Download for iOS" di halaman produk.
 *
 * Admin bisa mengisi link masing-masing dan memilih tombol mana yang tampil
 * (salah satu, keduanya, atau tidak sama sekali) lewat Admin > Produk.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('show_android_button')->default(false)->after('meta_description');
            $table->string('android_url', 500)->nullable()->after('show_android_button');
            $table->boolean('show_ios_button')->default(false)->after('android_url');
            $table->string('ios_url', 500)->nullable()->after('show_ios_button');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['show_android_button', 'android_url', 'show_ios_button', 'ios_url']);
        });
    }
};
