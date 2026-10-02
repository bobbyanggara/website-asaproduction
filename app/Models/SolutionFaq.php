<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionFaq extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['question', 'answer'];

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('id');
    }
}
