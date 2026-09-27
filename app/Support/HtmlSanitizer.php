<?php

namespace App\Support;

/**
 * Pembersih HTML sederhana (allow-list) untuk isi artikel blog yang sekarang
 * ditulis lewat rich text editor (lihat resources/views/admin/posts/form.blade.php).
 *
 * Kenapa bukan HTMLPurifier/composer package? Supaya tidak menambah dependency
 * baru yang butuh `composer install` ulang di server. Untuk kebutuhan di sini
 * (konten ditulis admin sendiri lewat toolbar terbatas, bukan HTML bebas dari
 * publik) allow-list manual berbasis DOMDocument ini cukup aman: tag/atribut
 * di luar daftar dibuang atau elemen "dibongkar" (anak-anaknya dipindah ke
 * induknya, tag-nya sendiri dihapus), dan skema URL berbahaya (javascript:,
 * data:, vbscript:) disaring dari href/src.
 *
 * Dipakai di dua tempat:
 *  - App\Support\Bilingual::body()   -> saat merender ke halaman publik
 *  - Admin\PostController            -> saat menyimpan, supaya yang tersimpan
 *                                        di database sudah bersih juga
 */
class HtmlSanitizer
{
    /** tag => daftar atribut yang diizinkan untuk tag itu */
    private const ALLOWED_TAGS = [
        'p' => ['class'],
        'br' => [],
        'hr' => [],
        'strong' => [],
        'b' => [],
        'em' => [],
        'i' => [],
        'u' => [],
        's' => [],
        'sub' => [],
        'sup' => [],
        'h2' => ['id'],
        'h3' => ['id'],
        'h4' => ['id'],
        'ul' => ['class'],
        'ol' => ['class'],
        'li' => [],
        'blockquote' => ['class'],
        'a' => ['href', 'target', 'rel', 'title'],
        'img' => ['src', 'alt', 'width', 'height', 'class'],
        'figure' => ['class'],
        'figcaption' => [],
        'pre' => ['class'],
        'code' => ['class'],
        'table' => ['class'],
        'thead' => [],
        'tbody' => [],
        'tr' => [],
        'th' => [],
        'td' => [],
        'span' => ['class'],
    ];

    private const DANGEROUS_URL = '/^\s*(javascript|data|vbscript)\s*:/i';

    public static function clean(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        // Bungkus dengan root sementara supaya fragment (bukan dokumen HTML utuh)
        // bisa di-parse, lalu ambil isinya lagi di bawah.
        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div id="__root__">'.$html.'</div>',
            LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED
        );
        libxml_clear_errors();

        $root = $dom->getElementById('__root__');
        if (! $root) {
            return '';
        }

        static::cleanChildren($root);

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }

        return trim($out);
    }

    private static function cleanChildren(\DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof \DOMText || $child instanceof \DOMCdataSection) {
                continue;
            }

            if (! $child instanceof \DOMElement) {
                $node->removeChild($child);

                continue;
            }

            $tag = strtolower($child->tagName);

            if ($tag === 'script' || $tag === 'style' || $tag === 'iframe' || $tag === 'object' || $tag === 'embed') {
                $node->removeChild($child);

                continue;
            }

            if (! array_key_exists($tag, self::ALLOWED_TAGS)) {
                // Tag tidak dikenal (mis. <div> hasil paste dari Word/Google Docs):
                // bongkar — pindahkan anak-anaknya ke induk, buang tag-nya saja.
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);

                continue;
            }

            static::stripAttributes($child, self::ALLOWED_TAGS[$tag]);

            if ($tag === 'a') {
                $child->setAttribute('rel', 'noopener noreferrer');
            }

            if ($tag === 'img') {
                $child->setAttribute('loading', 'lazy');
                // <img> tanpa src yang valid tidak berguna dan bisa jadi sisa paste rusak.
                if (trim((string) $child->getAttribute('src')) === '') {
                    $node->removeChild($child);

                    continue;
                }
            }

            static::cleanChildren($child);
        }
    }

    private static function stripAttributes(\DOMElement $el, array $allowedAttrs): void
    {
        foreach (iterator_to_array($el->attributes ?? []) as $attr) {
            $name = strtolower($attr->name);

            if (str_starts_with($name, 'on') || ! in_array($name, $allowedAttrs, true)) {
                $el->removeAttribute($attr->name);

                continue;
            }

            if (($name === 'href' || $name === 'src') && preg_match(self::DANGEROUS_URL, $attr->value)) {
                $el->removeAttribute($attr->name);
            }
        }
    }

    /**
     * Perkiraan lama baca (menit), dihitung dari teks polos konten (±200 kata/menit).
     * Dipakai untuk badge "X menit baca" di halaman blog.
     */
    public static function readingMinutes(string $html): int
    {
        $text = trim(strip_tags($html));
        if ($text === '') {
            return 1;
        }

        $words = count(array_filter(preg_split('/\s+/u', $text) ?: []));

        return max(1, (int) ceil($words / 200));
    }
}
