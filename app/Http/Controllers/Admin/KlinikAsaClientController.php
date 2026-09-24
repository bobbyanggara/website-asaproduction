<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KlinikAsaClient;
use Illuminate\Http\Request;

class KlinikAsaClientController extends Controller
{
    public function index()
    {
        $items = KlinikAsaClient::ordered()->get();

        return view('admin.klinik-asa-clients.index', compact('items'));
    }

    public function create()
    {
        return view('admin.klinik-asa-clients.form', ['item' => new KlinikAsaClient()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo'] = $request->file('logo')->store('klinik-asa-clients', 'public');

        KlinikAsaClient::create($data);

        return redirect()->route('admin.klinik-asa-clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(KlinikAsaClient $klinikAsaClient)
    {
        return view('admin.klinik-asa-clients.form', ['item' => $klinikAsaClient]);
    }

    public function update(Request $request, KlinikAsaClient $klinikAsaClient)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('klinik-asa-clients', 'public');
        }

        $klinikAsaClient->update($data);

        return redirect()->route('admin.klinik-asa-clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(KlinikAsaClient $klinikAsaClient)
    {
        $klinikAsaClient->delete();

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
