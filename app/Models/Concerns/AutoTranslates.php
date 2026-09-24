<?php

namespace App\Models\Concerns;

use App\Services\AutoTranslateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Terjemahan otomatis Indonesia -> Inggris untuk model konten.
 *
 * Cara pakai di model:
 *
 *     use AutoTranslates;
 *     protected array $translatable = ['title', 'description'];
 *
 * Untuk tiap field di $translatable harus ada kolom pasangan "<field>_en"
 * (dibuat migration 2026_09_26_000001). Saat admin menyimpan:
 *
 *  - Kolom EN kosong                       -> diterjemahkan otomatis.
 *  - Teks Indonesia berubah, EN tidak      -> EN diterjemahkan ulang (biar tidak basi).
 *  - Admin mengetik EN sendiri (form admin) -> dipakai apa adanya, tidak ditimpa.
 *
 * Kalau API terjemahan gagal / belum dikonfigurasi, penyimpanan TETAP berhasil;
 * kolom EN dibiarkan kosong (halaman publik jatuh ke teks Indonesia) dan dicoba
 * lagi saat simpan berikutnya atau lewat Admin > Terjemahan.
 *
 * Terjemahan hanya berjalan untuk request admin (POST/PUT/PATCH ke /admin/*),
 * jadi seeder, artisan, dan pengunjung publik tidak pernah memicu panggilan API.
 */
trait AutoTranslates
{
    /** @var array<class-string, array<int, string>> cache daftar kolom tabel per model */
    protected static array $translationColumnCache = [];

    public static function bootAutoTranslates(): void
    {
        static::saving(function ($model) {
            try {
                $model->applyAutoTranslation();
            } catch (\Throwable $e) {
                // Terjemahan tidak boleh menggagalkan penyimpanan konten.
                Log::error('AutoTranslate: gagal pada '.static::class, ['message' => $e->getMessage()]);
            }
        });
    }

    /** Nama field Indonesia yang punya pasangan kolom "_en" di database. */
    public static function translatableColumns(): array
    {
        $declared = (new static)->translatable ?? [];

        $table = (new static)->getTable();
        static::$translationColumnCache[static::class] ??= Schema::getColumnListing($table);
        $existing = static::$translationColumnCache[static::class];

        return array_values(array_filter(
            $declared,
            fn (string $field) => in_array($field, $existing, true) && in_array($field.'_en', $existing, true)
        ));
    }

    /**
     * Metadata field untuk form admin: field => ['label' => ..., 'multiline' => bool].
     */
    public static function translatableFields(): array
    {
        $labels = [
            'title' => 'Judul',
            'description' => 'Deskripsi',
            'question' => 'Pertanyaan',
            'answer' => 'Jawaban',
            'message' => 'Isi testimoni',
            'role' => 'Jabatan',
            'category' => 'Kategori',
            'excerpt' => 'Ringkasan',
            'content' => 'Isi artikel',
            'eyebrow' => 'Teks kecil di atas judul',
            'hero_title' => 'Judul Hero',
            'hero_subtitle' => 'Subjudul Hero',
            'preview_title' => 'Judul Preview',
            'preview_caption' => 'Keterangan Preview',
            'nav_description' => 'Deskripsi di navbar',
            'promo_text' => 'Teks Promo',
        ];
        $multiline = ['description', 'answer', 'message', 'excerpt', 'content', 'hero_subtitle', 'preview_caption'];

        $out = [];
        foreach (static::translatableColumns() as $field) {
            $out[$field] = [
                'label' => $labels[$field] ?? Str::headline($field),
                'multiline' => in_array($field, $multiline, true),
            ];
        }

        return $out;
    }

    /** Baris yang punya teks Indonesia tapi versi Inggrisnya masih kosong. */
    public function scopeNeedsTranslation(Builder $query): Builder
    {
        $fields = static::translatableColumns();

        if (! $fields) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function (Builder $q) use ($fields) {
            foreach ($fields as $field) {
                $q->orWhere(function (Builder $w) use ($field) {
                    $w->whereNotNull($field)->where($field, '!=', '')
                        ->where(fn (Builder $e) => $e->whereNull($field.'_en')->orWhere($field.'_en', ''));
                });
            }
        });
    }

    /**
     * Terjemahkan sekarang (dipakai backfill: artisan & Admin > Terjemahan).
     * $force = terjemahkan ulang semua field, termasuk yang sudah ada EN-nya.
     * Return jumlah field yang berhasil diterjemahkan.
     */
    public function translateNow(bool $force = false): int
    {
        $inputs = [];
        foreach (static::translatableColumns() as $field) {
            $id = $this->getAttribute($field);
            if (blank($id)) {
                continue;
            }
            if ($force || blank($this->getAttribute($field.'_en'))) {
                $inputs[$field.'_en'] = (string) $id;
            }
        }

        if (! $inputs) {
            return 0;
        }

        $translated = app(AutoTranslateService::class)->translateBatch($inputs);

        foreach ($translated as $column => $value) {
            $this->setAttribute($column, $value);
        }

        if ($this->isDirty()) {
            $hadTimestamps = $this->timestamps;
            $this->timestamps = false;      // backfill tidak mengubah "updated_at"
            $this->saveQuietly();           // tanpa event -> tidak memicu terjemahan lagi
            $this->timestamps = $hadTimestamps;
        }

        return count($translated);
    }

    /** Request ini penulisan dari halaman admin? (bukan seeder/artisan/pengunjung) */
    protected function isAdminWrite(): bool
    {
        if (app()->runningInConsole()) {
            return false;
        }

        $request = request();

        return $request->is('admin/*') && in_array($request->method(), ['POST', 'PUT', 'PATCH'], true);
    }

    protected function applyAutoTranslation(): void
    {
        $fields = static::translatableColumns();
        if (! $fields) {
            return;
        }

        $adminWrite = $this->isAdminWrite();
        $todo = [];   // kolom_en => ['text' => teks ID, 'stale' => EN lama sudah tidak cocok]

        foreach ($fields as $field) {
            $en = $field.'_en';
            $idText = $this->getAttribute($field);

            if (blank($idText)) {
                // Teks Indonesia dikosongkan -> versi Inggrisnya ikut dikosongkan.
                if ($this->isDirty($field)) {
                    $this->setAttribute($en, null);
                }

                continue;
            }

            // Field EN yang dikirim form admin (diketik manual atau hasil terjemahan sebelumnya).
            if ($adminWrite && request()->has($en)) {
                $submitted = request()->input($en);
                $submitted = is_string($submitted) ? trim($submitted) : '';
                $this->setAttribute($en, $submitted !== '' ? $submitted : null);
            }

            $current = $this->getAttribute($en);
            $manualEdit = filled($current) && $this->isDirty($en);
            $stale = $this->isDirty($field) && ! $manualEdit && filled($current);

            if (blank($current) || ($this->isDirty($field) && ! $manualEdit)) {
                $todo[$en] = ['text' => (string) $idText, 'stale' => $stale];
            }
        }

        if (! $todo) {
            return;
        }

        $translated = [];
        if ($adminWrite && config('services.auto_translate.enabled', true)) {
            $translated = app(AutoTranslateService::class)
                ->translateBatch(array_map(fn ($item) => $item['text'], $todo));
        }

        foreach ($todo as $en => $item) {
            if (isset($translated[$en])) {
                $this->setAttribute($en, $translated[$en]);
            } elseif ($item['stale']) {
                // Terjemahan lama sudah tidak sesuai dengan teks Indonesia yang baru,
                // dan yang baru gagal dibuat -> kosongkan (tampil Indonesia) daripada salah.
                $this->setAttribute($en, null);
            }
        }
    }
}
