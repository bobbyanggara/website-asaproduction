<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenAsaClient;
use Illuminate\Http\Request;

class AbsenAsaClientController extends Controller
{
    public function index()
    {
        $items = AbsenAsaClient::ordered()->get();

        return view('admin.absen-asa-clients.index', compact('items'));
    }

    public function create()
    {
        return view('admin.absen-asa-clients.form', ['item' => new AbsenAsaClient()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo'] = $request->file('logo')->store('absen-asa-clients', 'public');

        AbsenAsaClient::create($data);

        return redirect()->route('admin.absen-asa-clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(AbsenAsaClient $absenAsaClient)
    {
        return view('admin.absen-asa-clients.form', ['item' => $absenAsaClient]);
    }

    public function update(Request $request, AbsenAsaClient $absenAsaClient)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('absen-asa-clients', 'public');
        }

        $absenAsaClient->update($data);

        return redirect()->route('admin.absen-asa-clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(AbsenAsaClient $absenAsaClient)
    {
        $absenAsaClient->delete();

        return back()->with('success', 'Klien berhasil dihapus.');
    }

    private function validated(Request $request, bool $logoRequired): array
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => [$logoRequired ? 'required' : 'nullable', 'image', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['logo']);

        return $data;
    }
}
