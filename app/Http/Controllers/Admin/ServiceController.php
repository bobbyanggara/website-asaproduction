<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $items = Service::ordered()->get();

        return view('admin.services.index', compact('items'));
    }

    public function create()
    {
        return view('admin.services.form', ['item' => new Service()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Keunggulan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', ['item' => $service]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            // Ada file baru diupload: pakai file baru, timpa ikon lama (termasuk kelas CSS).
            $data['icon'] = $request->file('icon_file')->store('services', 'public');
        } elseif ($data['icon'] === null || $data['icon'] === '') {
            // Tidak ada file baru & field kelas CSS dikosongkan (kondisi normal saat
            // layanan sedang memakai foto upload) -> pertahankan ikon/foto lama,
            // jangan ditimpa string kosong.
            unset($data['icon']);
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Keunggulan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Keunggulan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'icon_file' => ['nullable', 'image', 'max:1024'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['icon_file']);

        return $data;
    }
}
