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
     * Isi panjang berparagraf (pemisah baris kosong), tiap paragraf jadi <p>.
     * Paragraf ID dan EN dirender sebagai dua kelompok <p> terpisah.
     */
    public static function body($model, string $field): HtmlString
    {
        if (! $model) {
            return new HtmlString('');
        }

        $id = (string) $model->getAttribute($field);
        $en = trim((string) $model->getAttribute($field.'_en'));

        $paragraphs = function (string $text, string $attrs): string {
            $html = '';
            foreach (preg_split('/\R{2,}/u', trim($text)) ?: [] as $paragraph) {
                $paragraph = trim($paragraph);
                if ($paragraph !== '') {
                    $html .= '<p'.$attrs.'>'.nl2br(e($paragraph)).'</p>';
                }
            }

            return $html;
        };

        if ($en === '' || $en === trim($id)) {
            return new HtmlString($paragraphs($id, ''));
        }

        return new HtmlString(
            $paragraphs($id, ' data-i18n-lang="id"').$paragraphs($en, ' data-i18n-lang="en" style="display:none"')
        );
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
