<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomSystemClient;
use Illuminate\Http\Request;

class CustomSystemClientController extends Controller
{
    public function index()
    {
        $items = CustomSystemClient::ordered()->get();

        return view('admin.custom-system-clients.index', compact('items'));
    }

    public function create()
    {
        return view('admin.custom-system-clients.form', ['item' => new CustomSystemClient()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo'] = $request->file('logo')->store('custom-system-clients', 'public');

        CustomSystemClient::create($data);

        return redirect()->route('admin.custom-system-clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(CustomSystemClient $customSystemClient)
    {
        return view('admin.custom-system-clients.form', ['item' => $customSystemClient]);
    }

    public function update(Request $request, CustomSystemClient $customSystemClient)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('custom-system-clients', 'public');
        }

        $customSystemClient->update($data);

        return redirect()->route('admin.custom-system-clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(CustomSystemClient $customSystemClient)
    {
        $customSystemClient->delete();

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
