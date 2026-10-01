<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalApp;
use App\Models\LegalPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Admin > Halaman Legal > tambah / ubah / hapus APLIKASI.
 * Tiap aplikasi otomatis punya dua dokumen: Ketentuan Layanan & Kebijakan Privasi.
 */
class LegalAppController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validateWithBag('newApp', [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:300'],
        ]);

        $app = LegalApp::create([
            'name' => $data['name'],
            'slug' => LegalApp::uniqueSlug(filled($data['slug'] ?? null) ? $data['slug'] : $data['name']),
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);

        // Siapkan kedua dokumen dari template bawaan supaya langsung bisa diedit.
        foreach (LegalPage::SLUGS as $slug) {
            LegalPage::forSlug($slug, $app);
        }

        return redirect()->route('admin.legal-pages.apps.show', $app)
            ->with('success', 'Aplikasi "'.$app->name.'" ditambahkan. Dokumen awalnya dibuat dari template; silakan sesuaikan.');
    }

    public function update(Request $request, LegalApp $legalApp)
    {
        $data = $request->validateWithBag('appSettings', [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:300'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($data['slug']) ?: Str::slug($data['name']);
        if (LegalApp::isReserved($slug)) {
            return back()->withInput()->withErrors(['slug' => 'Alamat (slug) ini dicadangkan oleh situs. Pilih yang lain.'], 'appSettings');
        }
        if (LegalApp::where('slug', $slug)->where('id', '!=', $legalApp->id)->exists()) {
            return back()->withInput()->withErrors(['slug' => 'Alamat (slug) sudah dipakai aplikasi lain.'], 'appSettings');
        }

        $legalApp->update([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.legal-pages.apps.show', $legalApp)
            ->with('success', 'Pengaturan aplikasi "'.$legalApp->name.'" disimpan.');
    }

    public function destroy(LegalApp $legalApp)
    {
        $name = $legalApp->name;
        $legalApp->delete(); // dokumen milik aplikasi ikut terhapus (cascade)

        return redirect()->route('admin.legal-pages.index')
            ->with('success', 'Aplikasi "'.$name.'" beserta dokumen legalnya dihapus.');
    }
}
