<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Solution extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['name', 'eyebrow', 'hero_title', 'hero_subtitle', 'preview_title', 'preview_caption', 'nav_description'];

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Kunci cache untuk daftar solusi di navbar & footer. */
    public const NAV_CACHE_KEY = 'nav.solutions';

    /**
     * Slug yang tidak boleh dipakai karena bentrok dengan route lain di bawah /solusi/.
     * (/solusi/ketentuan-layanan akan ditangkap route legal /{appSlug}/ketentuan-layanan.)
     */
    public const RESERVED_SLUGS = ['ketentuan-layanan', 'kebijakan-privasi'];

    protected static function booted(): void
    {
        // Setiap solusi ditambah / diubah / dihapus, navbar & footer langsung
        // ikut (cache dibuang), jadi admin tidak perlu menunggu apa pun.
        $flush = fn () => Cache::forget(self::NAV_CACHE_KEY);

        static::saved($flush);
        static::deleted($flush);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('id');
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(SolutionBenefit::class)->orderBy('order_index')->orderBy('id');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(SolutionClient::class)->orderBy('order_index')->orderBy('id');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(SolutionPortfolio::class)->orderBy('order_index')->orderBy('id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(SolutionFaq::class)->orderBy('order_index')->orderBy('id');
    }

    /**
     * Solusi aktif untuk dropdown "Solusi" di navbar dan kolom "Layanan" di
     * footer. Di-cache supaya tidak ada query tambahan di tiap page view;
     * cache otomatis dibuang saat solusi berubah (lihat booted()).
     *
     * Yang disimpan di cache adalah ARRAY biasa (bukan Collection/model
     * Eloquent), supaya cache tidak rusak kalau kelas model berubah di
     * deploy berikutnya. Baru setelah dibaca diubah lagi jadi objek ringan.
     */
    public static function navSolutions()
    {
        $columns = ['id', 'name', 'name_en', 'slug', 'nav_icon', 'nav_description', 'nav_description_en'];

        $rows = Cache::rememberForever(self::NAV_CACHE_KEY, function () use ($columns) {
            return static::active()->ordered()
                ->get($columns)
                ->map(fn ($s) => $s->only($columns))
                ->all();
        });

        return collect($rows)->map(fn (array $row) => (new static)->forceFill($row));
    }

    /** Ikon dengan fallback, supaya solusi tanpa ikon tetap punya ikon di navbar. */
    public function navIconClass(): string
    {
        return $this->nav_icon ?: 'fa-briefcase';
    }

    /** Buat slug unik dari nama (dipakai saat admin membuat halaman solusi). */
    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'solusi';
        $slug = $base;
        $i = 2;

        while (in_array($slug, self::RESERVED_SLUGS, true)
            || static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
