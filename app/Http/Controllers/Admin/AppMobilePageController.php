<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppMobilePage;
use Illuminate\Http\Request;

class AppMobilePageController extends Controller
{
    public function edit()
    {
        $page = AppMobilePage::current();

        return view('admin.app-mobile.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = AppMobilePage::current();

        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'preview_title' => ['nullable', 'string', 'max:255'],
            'preview_caption' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'max:2048'],
            // gif diperbolehkan lewat aturan 'image'; video ditambahkan lewat mimes eksplisit.
            'preview_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm', 'max:20480'],
        ]);

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')->store('app-mobile-page', 'public');
        } else {
            unset($data['hero_image']);
        }

        if ($request->hasFile('preview_media')) {
            $data['preview_media'] = $request->file('preview_media')->store('app-mobile-page', 'public');
        } else {
            unset($data['preview_media']);
        }

        $page->update($data);

        return back()->with('success', 'Konten halaman berhasil disimpan.');
    }
}
