<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Product extends Model
{
    use AutoTranslates;

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['eyebrow', 'hero_title', 'hero_subtitle', 'preview_title', 'preview_caption', 'nav_description'];

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'show_android_button' => 'boolean',
        'show_ios_button' => 'boolean',
    ];

    /** Kunci cache untuk daftar produk di navbar. */
    public const NAV_CACHE_KEY = 'nav.products';

    protected static function booted(): void
    {
        // Setiap produk ditambah / diubah / dihapus, navbar langsung ikut
        // (cache dibuang), jadi admin tidak perlu menunggu apa pun.
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
        return $this->hasMany(ProductBenefit::class)->orderBy('order_index')->orderBy('id');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(ProductClient::class)->orderBy('order_index')->orderBy('id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->orderBy('order_index')->orderBy('id');
    }

    /**
     * Produk aktif untuk dropdown "Produk" di navbar. Di-cache supaya tidak
     * ada query tambahan di tiap page view; cache otomatis dibuang saat
     * produk berubah (lihat booted()).
     *
     * Yang disimpan di cache adalah ARRAY biasa (bukan Collection/model
     * Eloquent), supaya cache tidak rusak kalau kelas model berubah di
     * deploy berikutnya. Baru setelah dibaca diubah lagi jadi objek ringan.
     */
    public static function navProducts()
    {
        $rows = Cache::rememberForever(self::NAV_CACHE_KEY, function () {
            return static::active()->ordered()
                ->get(['id', 'name', 'slug', 'nav_icon', 'nav_description', 'nav_description_en'])
                ->map(fn ($p) => $p->only(['id', 'name', 'slug', 'nav_icon', 'nav_description', 'nav_description_en']))
                ->all();
        });

        return collect($rows)->map(fn (array $row) => (new static)->forceFill($row));
    }

    /** Tombol Android tampil hanya kalau dicentang admin DAN link-nya terisi. */
    public function showsAndroidButton(): bool
    {
        return $this->show_android_button && filled($this->android_url);
    }

    /** Tombol iOS tampil hanya kalau dicentang admin DAN link-nya terisi. */
    public function showsIosButton(): bool
    {
        return $this->show_ios_button && filled($this->ios_url);
    }

    /** Ikon dengan fallback, supaya produk tanpa ikon tetap punya ikon di navbar. */
    public function navIconClass(): string
    {
        return $this->nav_icon ?: 'fa-cube';
    }

    /** Buat slug unik dari nama (dipakai saat admin membuat produk). */
    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
