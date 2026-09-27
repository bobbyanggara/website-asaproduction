<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Helper Blade untuk teks dari database yang punya versi Indonesia + Inggris.
 *
 * Bahasa di situs ini diganti di sisi browser (tanpa reload) oleh
 * public/js/i18n-lib.js, yang menampilkan/menyembunyikan elemen
 * [data-i18n-lang="id|en"]. Jadi kedua versi ditulis ke HTML, dan yang tidak
 * aktif disembunyikan. Kalau versi Inggris belum ada (atau sama persis dengan
 * Indonesia), hanya satu teks yang ditulis dan tampil di kedua bahasa.
 *
 * Di Blade (lihat AppServiceProvider):
 *
 *     @bi($service, 'title')                          -> <h3>@bi($service, 'title')</h3>
 *     @bi($set, 'about_title', ['nl2br' => true])     -> baris baru jadi <br>
 *     @bipair($idText, $enText)                       -> pasangan teks bebas
 *     @bibody($post, 'content')                       -> isi artikel, per paragraf <p>
 */
class Bilingual
{
    public static function field($model, string $field, array $opts = []): HtmlString
    {
        if (! $model) {
            return new HtmlString('');
        }

        return static::pair(
            $model->getAttribute($field),
            $model->getAttribute($field.'_en'),
            $opts
        );
    }

    public static function pair($id, $en, array $opts = []): HtmlString
    {
        $id = trim((string) $id);
        $en = trim((string) $en);

        if ($id === '' && $en === '') {
            return new HtmlString('');
        }

        if ($id === '') {
            $id = $en;
        }

        // Belum ada terjemahan (atau memang sama, mis. nama brand): satu teks untuk semua bahasa.
        if ($en === '' || $en === $id) {
            return new HtmlString(static::format($id, $opts));
        }

        return new HtmlString(
            '<span data-i18n-lang="id">'.static::format($id, $opts).'</span>'
            .'<span data-i18n-lang="en" style="display:none">'.static::format($en, $opts).'</span>'
        );
    }

    /**
     * Isi artikel (mis. Post::content). Sejak editor artikel jadi rich text
     * (lihat admin/posts/form.blade.php), field ini bisa berisi HTML dengan
     * heading, list, blockquote, gambar, dll — dibersihkan lewat HtmlSanitizer
     * lalu dirender apa adanya. Artikel lama yang masih teks polos (sebelum
     * rich editor ada) tetap didukung: dipecah per baris kosong jadi <p>
     * seperti perilaku lama, supaya tidak ada artikel lama yang rusak.
     */
    public static function body($model, string $field): HtmlString
    {
        if (! $model) {
            return new HtmlString('');
        }

        $id = (string) $model->getAttribute($field);
        $en = trim((string) $model->getAttribute($field.'_en'));

        $render = function (string $text): string {
            $text = trim($text);
            if ($text === '') {
                return '';
            }

            if (static::looksLikeHtml($text)) {
                return \App\Support\HtmlSanitizer::clean($text);
            }

            // Artikel lama (teks polos): pecah per baris kosong jadi <p>.
            $html = '';
            foreach (preg_split('/\R{2,}/u', $text) ?: [] as $paragraph) {
                $paragraph = trim($paragraph);
                if ($paragraph !== '') {
                    $html .= '<p>'.nl2br(e($paragraph)).'</p>';
                }
            }

            return $html;
        };

        $idHtml = $render($id);
        $enHtml = $render($en);

        if ($enHtml === '' || $enHtml === $idHtml) {
            return new HtmlString($idHtml);
        }

        return new HtmlString(
            '<div data-i18n-lang="id">'.$idHtml.'</div>'
            .'<div data-i18n-lang="en" style="display:none">'.$enHtml.'</div>'
        );
    }

    /** Heuristik: teks dianggap HTML kalau mengandung tag blok yang dikenal editor artikel. */
    private static function looksLikeHtml(string $text): bool
    {
        return (bool) preg_match('/<(p|h[1-6]|ul|ol|li|blockquote|img|figure|table|pre)[\s>]/i', $text);
    }

    /** Ringkasan otomatis dari isi artikel kalau excerpt kosong (per bahasa). */
    public static function excerpt($model, int $limit = 100): HtmlString
    {
        if (! $model) {
            return new HtmlString('');
        }

        $make = fn (?string $t) => $t ? Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($t))), $limit) : '';

        $id = trim((string) $model->excerpt) !== '' ? (string) $model->excerpt : $make($model->content);
        $en = trim((string) $model->excerpt_en) !== '' ? (string) $model->excerpt_en : $make($model->content_en);

        // Excerpt Indonesia diisi tapi Inggrisnya belum ada: jangan campur dengan potongan isi EN.
        if (trim((string) $model->excerpt) !== '' && trim((string) $model->excerpt_en) === '') {
            $en = '';
        }

        return static::pair($id, $en);
    }

    private static function format(string $text, array $opts): string
    {
        if (! empty($opts['limit'])) {
            $text = Str::limit($text, (int) $opts['limit']);
        }

        return ! empty($opts['nl2br']) ? nl2br(e($text)) : e($text);
    }
}
