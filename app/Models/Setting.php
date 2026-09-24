<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['hero_title', 'hero_subtitle', 'about_label', 'about_title', 'about_description', 'about_cta_text', 'footer_title', 'footer_subtitle', 'footer_cta_text', 'promo_text'];

    protected $guarded = [];

    /**
     * Settings is a single-row table. Always fetch (or create) row #1.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
