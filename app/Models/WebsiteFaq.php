<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;

class WebsiteFaq extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['question', 'answer'];

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('id');
    }
}
