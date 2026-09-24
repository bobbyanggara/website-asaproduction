<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenAsaPage;
use Illuminate\Http\Request;

class AbsenAsaPageController extends Controller
{
    public function edit()
    {
        $page = AbsenAsaPage::current();

        return view('admin.absen-asa.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = AbsenAsaPage::current();

        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'preview_title' => ['nullable', 'string', 'max:255'],
            'preview_caption' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'max:2048'],
            'preview_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm', 'max:20480'],
        ]);

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')->store('absen-asa-page', 'public');
        } else {
            unset($data['hero_image']);
        }

        if ($request->hasFile('preview_media')) {
            $data['preview_media'] = $request->file('preview_media')->store('absen-asa-page', 'public');
        } else {
            unset($data['preview_media']);
        }

        $page->update($data);

        return back()->with('success', 'Konten halaman berhasil disimpan.');
    }
}
