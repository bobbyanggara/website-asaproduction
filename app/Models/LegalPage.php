<?php

namespace App\Models;

use App\Models\Concerns\AutoTranslates;
use App\Support\HtmlSanitizer;
use App\Support\LegalAppDefaults;
use App\Support\LegalDefaults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Halaman legal dinamis: "terms" (Ketentuan Layanan) dan "privacy" (Kebijakan Privasi).
 *
 * Isi diedit dari Admin > Halaman Legal. Baris dibuat otomatis dari
 * LegalDefaults::for($slug) saat pertama kali dibutuhkan (bukan lewat seeder),
 * sehingga halaman publik langsung punya isi profesional + versi Inggris
 * tanpa perlu menjalankan seeder atau memanggil API terjemahan.
 */
class LegalPage extends Model
{
    use AutoTranslates;

    public const SLUGS = ['terms', 'privacy'];

    /** Field Indonesia yang otomatis diterjemahkan ke kolom "<field>_en" saat admin menyimpan. */
    protected array $translatable = ['title', 'excerpt', 'content'];

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_date' => 'date',
    ];

    public function app()
    {
        return $this->belongsTo(LegalApp::class, 'legal_app_id');
    }

    /**
     * Ambil halaman berdasarkan slug; kalau belum ada, buat dari isi bawaan.
     * $app = null -> halaman umum situs; $app terisi -> dokumen milik aplikasi itu.
     */
    public static function forSlug(string $slug, ?LegalApp $app = null): self
    {
        abort_unless(in_array($slug, self::SLUGS, true), 404);

        return static::firstOrCreate(
            ['slug' => $slug, 'legal_app_id' => $app?->id],
            $app ? LegalAppDefaults::for($slug) : LegalDefaults::for($slug)
        );
    }

    public function publicUrl(): string
    {
        if ($this->legal_app_id) {
            return route(
                $this->slug === 'terms' ? 'legal.app.terms' : 'legal.app.privacy',
                $this->app->slug
            );
        }

        return route($this->slug === 'terms' ? 'legal.terms' : 'legal.privacy');
    }

    /** Token yang bisa dipakai admin di isi halaman, diganti otomatis saat ditampilkan. */
    public static function tokens(Setting $setting, ?LegalApp $app = null): array
    {
        return [
            '{app}' => $app?->name ?: ($setting->site_title ?: 'Asa Production'),
            '{site}' => $setting->site_title ?: 'Asa Production',
            '{email}' => $setting->footer_email ?: '',
            '{address}' => $setting->footer_address ?: '',
            '{phone}' => $setting->whatsapp_number ?: '',
            '{website}' => url('/'),
        ];
    }

    /** Ganti token pada teks polos (judul/ringkasan/meta). Tidak di-escape: Blade yang meng-escape. */
    public static function replaceTokens(?string $text, Setting $setting, ?LegalApp $app = null): string
    {
        return strtr((string) $text, static::tokens($setting, $app));
    }

    /**
     * Siapkan isi untuk ditampilkan: ganti token {site}/{email}/{address}/{phone}/{website},
     * bersihkan HTML, beri id pada tiap <h2>, dan kumpulkan daftar isi.
     *
     * @return array{html: string, toc: array<int, array{id: string, text: string}>}
     */
    public static function prepare(?string $html, Setting $setting, string $idPrefix, ?LegalApp $app = null): array
    {
        $html = trim((string) $html);
        if ($html === '') {
            return ['html' => '', 'toc' => []];
        }

        // Nilai token di-escape dulu karena disisipkan ke dalam HTML.
        $html = strtr($html, array_map(fn ($v) => e($v), static::tokens($setting, $app)));
        $html = HtmlSanitizer::clean($html);

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div id="__root__">'.$html.'</div>',
            LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED
        );
        libxml_clear_errors();

        $root = $dom->getElementById('__root__');
        if (! $root) {
            return ['html' => $html, 'toc' => []];
        }

        $toc = [];
        $n = 0;
        foreach ($root->getElementsByTagName('h2') as $h2) {
            $text = trim(preg_replace('/\s+/u', ' ', $h2->textContent));
            if ($text === '') {
                continue;
            }
            $id = $idPrefix.'-sec-'.(++$n);
            $h2->setAttribute('id', $id);
            $toc[] = ['id' => $id, 'text' => $text];
        }

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }

        return ['html' => trim($out), 'toc' => $toc];
    }

    /** Aman dipakai di <meta description>: teks polos dari ringkasan/isi. */
    public function metaDescription(Setting $setting): string
    {
        $source = filled($this->meta_description)
            ? $this->meta_description
            : (filled($this->excerpt) ? $this->excerpt : strip_tags((string) $this->content));

        return Str::limit(trim(preg_replace('/\s+/u', ' ', static::replaceTokens($source, $setting, $this->app))), 155);
    }
}
