<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SolutionController extends Controller
{
    public function index()
    {
        $items = Solution::ordered()->withCount(['benefits', 'clients', 'portfolios', 'faqs'])->get();

        return view('admin.solutions.index', compact('items'));
    }

    public function create()
    {
        return view('admin.solutions.form', ['item' => new Solution(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Solution::uniqueSlug($data['slug'] ?: $data['name']);
        $data = $this->handleUploads($request, $data);

        $solution = Solution::create($data);

        return redirect()
            ->route('admin.solutions.edit', $solution)
            ->with('success', 'Halaman solusi "'.$solution->name.'" berhasil dibuat dan sudah tampil di navbar. Lengkapi Keunggulan, Klien, Portofolio, dan FAQ-nya di bawah.');
    }

    public function edit(Solution $solution)
    {
        $solution->loadCount(['benefits', 'clients', 'portfolios', 'faqs']);

        return view('admin.solutions.form', ['item' => $solution]);
    }

    public function update(Request $request, Solution $solution)
    {
        $data = $this->validated($request, $solution);

        // Slug kosong saat EDIT = pertahankan slug yang ada. Jangan diturunkan
        // ulang dari nama, karena itu diam-diam mengubah URL halaman (dan
        // memutus link lama) hanya karena admin mengganti nama.
        $data['slug'] = filled($data['slug'] ?? null)
            ? Solution::uniqueSlug($data['slug'], $solution->id)
            : $solution->slug;

        $data = $this->handleUploads($request, $data, $solution);

        $solution->update($data);

        return back()->with('success', 'Halaman solusi berhasil disimpan.');
    }

    public function destroy(Solution $solution)
    {
        // File upload milik halaman ini ikut dibersihkan supaya storage tidak
        // menumpuk sampah. Keunggulan/klien/portofolio/FAQ terhapus otomatis
        // lewat FK cascade.
        foreach (['hero_image', 'preview_media'] as $field) {
            if ($solution->{$field}) {
                Storage::disk('public')->delete($solution->{$field});
            }
        }
        foreach ($solution->clients as $client) {
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
        }
        foreach ($solution->benefits as $benefit) {
            if ($benefit->icon && str_contains($benefit->icon, '/')) {
                Storage::disk('public')->delete($benefit->icon);
            }
        }
        // Gambar portofolio SENGAJA tidak dihapus: filenya bisa dipakai bersama
        // Portofolio global (item hasil "Pilih dari Portofolio" menunjuk file yang sama).

        $solution->delete();

        return redirect()->route('admin.solutions.index')->with('success', 'Halaman solusi berhasil dihapus.');
    }

    private function validated(Request $request, ?Solution $solution = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(Solution::RESERVED_SLUGS),
                Rule::unique('solutions', 'slug')->ignore($solution?->id),
            ],
            'nav_icon' => ['nullable', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'nav_description' => ['nullable', 'string', 'max:255'],
            'nav_description_en' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'preview_title' => ['nullable', 'string', 'max:255'],
            'preview_caption' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'order_index' => ['nullable', 'integer'],
            'hero_image' => ['nullable', 'image', 'max:2048'],
            'preview_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm', 'max:20480'],
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung (contoh: jasa-desain-grafis).',
            'slug.unique' => 'Slug ini sudah dipakai halaman solusi lain.',
            'slug.not_in' => 'Slug ini dipakai oleh halaman sistem, pilih slug lain.',
            'nav_icon.regex' => 'Ikon harus berupa kelas font-awesome, contoh: fa-desktop.',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order_index'] = $data['order_index'] ?? 0;

        return $data;
    }

    /** Simpan file upload; kalau tidak ada upload baru, jangan timpa nilai lama. */
    private function handleUploads(Request $request, array $data, ?Solution $solution = null): array
    {
        foreach (['hero_image', 'preview_media'] as $field) {
            if ($request->hasFile($field)) {
                if ($solution && $solution->{$field}) {
                    Storage::disk('public')->delete($solution->{$field});
                }
                $data[$field] = $request->file($field)->store('solutions', 'public');
            } else {
                unset($data[$field]);
            }
        }

        return $data;
    }
}
