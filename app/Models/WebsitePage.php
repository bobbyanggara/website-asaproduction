<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;

class WebsitePage extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['hero_title', 'hero_subtitle', 'preview_title', 'preview_caption'];

    protected $guarded = [];

    /**
     * Halaman ini kontennya 1 baris saja (mirip Setting). Selalu ambil
     * (atau buat) baris #1.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
