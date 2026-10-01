<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Aplikasi yang punya dokumen legal sendiri (Ketentuan Layanan & Kebijakan Privasi),
 * mis. "Kalkulator Shopee Seller". Dikelola dari Admin > Halaman Legal.
 */
class LegalApp extends Model
{
    /**
     * Slug yang tidak boleh dipakai aplikasi karena sudah jadi alamat/folder situs
     * (alamat publik aplikasi = /{slug}/ketentuan-layanan).
     */
    public const RESERVED = [
        'admin', 'login', 'logout', 'register', 'password', 'api', 'storage', 'build', 'vendor',
        'css', 'js', 'images', 'fonts', 'assets', 'up', 'legal', 'legal-pages', 'blog', 'posts',
        'produk', 'products', 'portofolio', 'portfolio', 'solusi', 'kontak', 'tentang-asa-production',
        'ketentuan-layanan', 'kebijakan-privasi', 'chat', 'partners', 'website', 'app-mobile',
        'custom-system', 'services', 'team', 'testimonials', 'faqs', 'certificates', 'settings',
        'translations', 'guest',
    ];

    protected $guarded = [];

    public static function isReserved(string $slug): bool
    {
        return in_array($slug, self::RESERVED, true);
    }

    protected $casts = ['is_active' => 'boolean'];

    public function pages()
    {
        return $this->hasMany(LegalPage::class);
    }

    /** Slug unik dari teks bebas; kalau sudah dipakai (atau dicadangkan situs) ditambah -2, -3, dst. */
    public static function uniqueSlug(string $base): string
    {
        $root = Str::slug($base) ?: 'aplikasi';
        $slug = $root;
        $i = 2;
        while (static::isReserved($slug) || static::where('slug', $slug)->exists()) {
            $slug = $root.'-'.$i++;
        }

        return $slug;
    }
}
