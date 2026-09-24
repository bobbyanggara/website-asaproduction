<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Menerjemahkan teks Bahasa Indonesia -> Inggris untuk konten website.
 *
 * TIDAK memakai Google Translate. Memakai Groq (LLM) yang memang sudah dipakai
 * chatbot di project ini, jadi cukup satu GROQ_API_KEY di .env. Hasilnya lebih
 * natural untuk teks marketing dibanding terjemahan kata-per-kata.
 *
 * Dipanggil otomatis oleh trait App\Models\Concerns\AutoTranslates saat admin
 * menyimpan konten, jadi admin tidak perlu menerjemahkan manual.
 */
class AutoTranslateService
{
    /** Teks di atas batas ini dipotong per paragraf supaya jawaban tidak terpotong. */
    private const CHUNK_CHARS = 3500;

    /** Field pendek digabung dalam satu panggilan API sampai batas karakter ini. */
    private const BATCH_CHARS = 6000;

    public function isConfigured(): bool
    {
        return filled(config('services.groq.key'));
    }

    /**
     * @param  array<string, string|null>  $texts  key => teks Indonesia
     * @return array<string, string>  key => teks Inggris (hanya key yang berhasil diterjemahkan)
     */
    public function translateBatch(array $texts): array
    {
        $texts = array_filter($texts, fn ($value) => filled($value));

        if (empty($texts)) {
            return [];
        }

        if (! $this->isConfigured()) {
            Log::warning('AutoTranslate: GROQ_API_KEY belum diset di .env, terjemahan dilewati.');

            return [];
        }

        $result = [];
        $pending = [];   // key => teks pendek yang menunggu digabung dalam satu panggilan
        $pendingSize = 0;

        foreach ($texts as $key => $text) {
            $text = (string) $text;

            // Sudah pernah diterjemahkan (teks identik) -> pakai cache, hemat kuota API.
            if (($cached = $this->cacheGet($text)) !== null) {
                $result[$key] = $cached;

                continue;
            }

            // Teks panjang (mis. isi artikel): dipotong per paragraf.
            if (mb_strlen($text) > self::CHUNK_CHARS) {
                $long = $this->translateLong($text);
                if ($long !== null) {
                    $result[$key] = $long;
                    $this->cachePut($text, $long);
                }

                continue;
            }

            if ($pendingSize + mb_strlen($text) > self::BATCH_CHARS && $pending) {
                $result += $this->flush($pending);
                $pending = [];
                $pendingSize = 0;
            }

            $pending[$key] = $text;
            $pendingSize += mb_strlen($text);
        }

        if ($pending) {
            $result += $this->flush($pending);
        }

        return $result;
    }

    /** Terjemahkan satu teks. null kalau gagal. */
    public function translate(string $text): ?string
    {
        return $this->translateBatch(['t' => $text])['t'] ?? null;
    }

    /** @param array<string, string> $pending */
    private function flush(array $pending): array
    {
        $translated = $this->callApi($pending);
        $out = [];

        foreach ($pending as $key => $original) {
            if (isset($translated[$key])) {
                $out[$key] = $translated[$key];
                $this->cachePut($original, $translated[$key]);
            }
        }

        return $out;
    }

    /** Potong per paragraf, terjemahkan tiap potongan, lalu gabung lagi. */
    private function translateLong(string $text): ?string
    {
        $chunks = $this->splitIntoChunks($text);
        $inputs = [];
        foreach ($chunks as $i => $chunk) {
            $inputs['c'.$i] = $chunk;
        }

        $translated = [];
        // Beberapa potongan per panggilan agar tidak terlalu banyak request.
        foreach (array_chunk($inputs, 2, true) as $group) {
            $translated += $this->callApi($group);
        }

        $parts = [];
        foreach (array_keys($chunks) as $i) {
            if (! isset($translated['c'.$i])) {
                // Satu potongan gagal -> batalkan semua. Lebih baik kosong (tampil
                // Indonesia, dicoba lagi saat simpan berikutnya) daripada campur dua bahasa.
                return null;
            }
            $parts[] = $translated['c'.$i];
        }

        return implode("\n\n", $parts);
    }

    /** @return array<int, string> */
    private function splitIntoChunks(string $text): array
    {
        $paragraphs = preg_split('/\R{2,}/u', trim($text)) ?: [$text];
        $chunks = [];
        $current = '';

        foreach ($paragraphs as $paragraph) {
            // Paragraf tunggal yang terlalu panjang dipecah per kalimat.
            $pieces = mb_strlen($paragraph) > self::CHUNK_CHARS
                ? (preg_split('/(?<=[.!?])\s+/u', $paragraph) ?: [$paragraph])
                : [$paragraph];

            foreach ($pieces as $piece) {
                $sep = $current === '' ? '' : "\n\n";
                if ($current !== '' && mb_strlen($current) + mb_strlen($piece) + 2 > self::CHUNK_CHARS) {
                    $chunks[] = $current;
                    $current = $piece;
                } else {
                    $current .= $sep.$piece;
                }
            }
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks;
    }

    /**
     * Satu panggilan Groq: JSON masuk -> JSON keluar.
     *
     * @param  array<string, string>  $texts
     * @return array<string, string>
     */
    private function callApi(array $texts): array
    {
        $payload = json_encode($texts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $prompt = "Terjemahkan setiap NILAI pada objek JSON berikut dari Bahasa Indonesia ke Bahasa Inggris.\n"
            ."Konteks: teks website perusahaan jasa IT & software (Asa Production). Pakai gaya profesional/marketing yang natural, bukan terjemahan kata-per-kata.\n"
            ."Aturan:\n"
            ."- Pertahankan setiap KEY persis sama.\n"
            ."- Pertahankan baris baru, emoji, tanda baca khusus, tag HTML, URL, email, angka, dan placeholder seperti {site}.\n"
            ."- Jangan menerjemahkan nama brand/produk/orang (mis. Asa Production, Absen ASA, Klinik ASA, WhatsApp).\n"
            ."- Jangan menambah penjelasan atau catatan.\n"
            ."Balas HANYA dengan objek JSON valid, tanpa teks lain dan tanpa markdown code fence.\n\n"
            .$payload;

        $maxTokens = (int) min(8000, max(1200, mb_strlen($payload) * 0.9));

        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $response = Http::withToken((string) config('services.groq.key'))
                    ->timeout(45)
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => config('services.groq.translate_model') ?: config('services.groq.model'),
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a precise JSON-in, JSON-out Indonesian-to-English translation engine for website content.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.2,
                        'max_tokens' => $maxTokens,
                        'response_format' => ['type' => 'json_object'],
                    ]);

                // Kena rate limit / server sibuk: tunggu sebentar lalu coba sekali lagi.
                if (($response->status() === 429 || $response->serverError()) && $attempt === 1) {
                    usleep(1_500_000);

                    continue;
                }

                if ($response->failed()) {
                    Log::error('AutoTranslate: Groq API error', ['status' => $response->status(), 'body' => $response->body()]);

                    return [];
                }

                $content = trim((string) $response->json('choices.0.message.content'));
                $decoded = json_decode($content, true);

                if (! is_array($decoded)) {
                    Log::error('AutoTranslate: respons bukan JSON valid', ['content' => $content]);

                    return [];
                }

                $result = [];
                foreach ($texts as $key => $original) {
                    if (isset($decoded[$key]) && is_string($decoded[$key]) && trim($decoded[$key]) !== '') {
                        $result[$key] = trim($decoded[$key]);
                    }
                }

                return $result;
            } catch (\Throwable $e) {
                Log::error('AutoTranslate: exception', ['message' => $e->getMessage()]);

                return [];
            }
        }

        return [];
    }

    private function cacheKey(string $text): string
    {
        return 'autotr.'.md5($text);
    }

    private function cacheGet(string $text): ?string
    {
        try {
            $value = Cache::get($this->cacheKey($text));

            return is_string($value) && $value !== '' ? $value : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function cachePut(string $text, string $translated): void
    {
        try {
            Cache::put($this->cacheKey($text), $translated, now()->addDays(60));
        } catch (\Throwable) {
            // cache tidak tersedia: abaikan, terjemahan tetap tersimpan di database.
        }
    }
}
