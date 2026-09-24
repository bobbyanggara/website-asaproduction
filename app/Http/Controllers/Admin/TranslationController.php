<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AutoTranslateService;
use App\Support\TranslatableModels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Halaman "Terjemahan": mengisi versi Inggris untuk konten LAMA (yang dibuat
 * sebelum fitur terjemahan otomatis ada). Konten baru / yang diedit lewat form
 * admin sudah otomatis diterjemahkan saat disimpan (lihat trait AutoTranslates).
 */
class TranslationController extends Controller
{
    private const BATCH = 8;          // baris per request
    private const TIME_BUDGET = 20;   // detik per request, jauh di bawah batas timeout server

    public function index(AutoTranslateService $translator)
    {
        $rows = [];

        foreach (TranslatableModels::all() as $key => $meta) {
            $class = $meta['class'];
            $rows[] = [
                'key' => $key,
                'label' => $meta['label'],
                'total' => $class::query()->count(),
                'pending' => $class::needsTranslation()->count(),
            ];
        }

        return view('admin.translations.index', [
            'rows' => $rows,
            'configured' => $translator->isConfigured(),
            'enabled' => (bool) config('services.auto_translate.enabled', true),
        ]);
    }

    /** Proses satu gelombang data (dipanggil berulang oleh JS di halaman). */
    public function run(Request $request): JsonResponse
    {
        $data = $request->validate([
            'model' => ['required', 'string', Rule::in(array_keys(TranslatableModels::all()))],
            'after_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $class = TranslatableModels::all()[$data['model']]['class'];
        $started = microtime(true);
        $done = 0;
        $failed = 0;
        $lastId = (int) ($data['after_id'] ?? 0);

        $rows = $class::needsTranslation()
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->limit(self::BATCH)
            ->get();

        foreach ($rows as $row) {
            if (microtime(true) - $started > self::TIME_BUDGET) {
                break;
            }

            $row->translateNow() > 0 ? $done++ : $failed++;
            $lastId = (int) $row->getKey();
        }

        return response()->json([
            'done' => $done,
            'failed' => $failed,
            'last_id' => $lastId,
            'has_more' => $class::needsTranslation()->where('id', '>', $lastId)->exists(),
            'pending' => $class::needsTranslation()->count(),
        ]);
    }
}
