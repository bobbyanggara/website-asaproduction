<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['title', 'excerpt', 'content'];

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /**
     * Bikin slug unik dari title kalau belum diisi manual.
     */
    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
