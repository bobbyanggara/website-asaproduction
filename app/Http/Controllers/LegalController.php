<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\LegalApp;
use App\Models\LegalPage;
use App\Models\Setting;

/**
 * Halaman publik Ketentuan Layanan & Kebijakan Privasi. Isinya dinamis dari
 * Admin > Halaman Legal.
 *
 *  - Umum situs : /ketentuan-layanan, /kebijakan-privasi
 *  - Per aplikasi: /{app}/ketentuan-layanan, /{app}/kebijakan-privasi
 *    (cocok dipakai sebagai URL kebijakan privasi di Play Store / App Store)
 */
class LegalController extends Controller
{
    public function terms()
    {
        return $this->show('terms');
    }

    public function privacy()
    {
        return $this->show('privacy');
    }

    public function appTerms(string $appSlug)
    {
        return $this->show('terms', $this->findApp($appSlug));
    }

    public function appPrivacy(string $appSlug)
    {
        return $this->show('privacy', $this->findApp($appSlug));
    }

    /** Aplikasi yang dinonaktifkan dari admin tidak boleh dibuka publik. */
    private function findApp(string $appSlug): LegalApp
    {
        return LegalApp::where('slug', $appSlug)->where('is_active', true)->firstOrFail();
    }

    private function show(string $slug, ?LegalApp $app = null)
    {
        $setting = Setting::current();
        $page = LegalPage::forSlug($slug, $app);

        // Halaman yang dinonaktifkan dari admin tidak boleh dibuka publik.
        abort_unless($page->is_active, 404);

        $idContent = LegalPage::prepare($page->content, $setting, 'id', $app);
        $enContent = LegalPage::prepare($page->content_en, $setting, 'en', $app);

        // Versi Inggris belum ada / sama persis: cukup satu versi untuk kedua bahasa
        // (pola yang sama dengan App\Support\Bilingual).
        $hasEn = $enContent['html'] !== '' && $enContent['html'] !== $idContent['html'];

        // Dibutuhkan supaya footer sama persis dengan footer landing page.
        $certificates = Certificate::active()->ordered()->get();

        return view('legal.show', [
            'setting' => $setting,
            'page' => $page,
            'legalApp' => $app,
            'idContent' => $idContent,
            'enContent' => $enContent,
            'hasEn' => $hasEn,
            'certificates' => $certificates,
            'activeSlug' => $slug,
        ]);
    }
}
