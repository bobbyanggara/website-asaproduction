<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalApp;
use App\Models\LegalPage;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Request;

/**
 * Admin > Halaman Legal.
 *
 * Alur: (1) pilih aplikasi (atau "Situs Asa Production" untuk halaman umum),
 * (2) lihat dokumennya (Ketentuan Layanan & Kebijakan Privasi), (3) edit isinya.
 * Hanya ada dua dokumen tetap per aplikasi (terms, privacy), jadi tidak ada tambah/hapus dokumen;
 * tambah/ubah/hapus APLIKASI ada di LegalAppController.
 */
class LegalPageController extends Controller
{
    /** Langkah 1: pilih aplikasi. */
    public function index()
    {
        $apps = LegalApp::orderBy('name')->get();

        return view('admin.legal-pages.index', compact('apps'));
    }

    /** Langkah 2: dokumen halaman umum situs. */
    public function general()
    {
        return $this->docs(null);
    }

    /** Langkah 2: dokumen milik satu aplikasi. */
    public function app(LegalApp $legalApp)
    {
        return $this->docs($legalApp);
    }

    private function docs(?LegalApp $app)
    {
        $items = collect(LegalPage::SLUGS)->map(fn (string $slug) => LegalPage::forSlug($slug, $app));

        return view('admin.legal-pages.docs', ['legalApp' => $app, 'items' => $items]);
    }

    /** Langkah 3: edit dokumen. */
    public function edit(string $slug)
    {
        return $this->form($slug, null);
    }

    public function editApp(LegalApp $legalApp, string $slug)
    {
        return $this->form($slug, $legalApp);
    }

    private function form(string $slug, ?LegalApp $app)
    {
        return view('admin.legal-pages.edit', [
            'item' => LegalPage::forSlug($slug, $app),
            'legalApp' => $app,
        ]);
    }

    public function update(Request $request, string $slug)
    {
        return $this->save($request, $slug, null);
    }

    public function updateApp(Request $request, LegalApp $legalApp, string $slug)
    {
        return $this->save($request, $slug, $legalApp);
    }

    private function save(Request $request, string $slug, ?LegalApp $app)
    {
        $page = LegalPage::forSlug($slug, $app);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:600'],
            'content' => ['required', 'string'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'effective_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Isi ditulis lewat rich text editor -> bersihkan HTML sebelum disimpan.
        $data['content'] = HtmlSanitizer::clean($data['content']);

        // Versi Inggris dari editor kedua. Dibersihkan juga; AutoTranslates membaca
        // field "content_en" dari request, jadi kita tulis balik ke request setelah dibersihkan.
        if ($request->has('content_en')) {
            $en = trim((string) $request->input('content_en'));
            $request->merge(['content_en' => $en !== '' ? HtmlSanitizer::clean($en) : null]);
        }

        $page->update($data);

        $route = $app
            ? redirect()->route('admin.legal-pages.apps.edit', [$app, $page->slug])
            : redirect()->route('admin.legal-pages.edit', $page->slug);

        return $route->with('success', 'Halaman "'.$page->title.'"'.($app ? ' ('.$app->name.')' : '').' berhasil disimpan.');
    }
}
