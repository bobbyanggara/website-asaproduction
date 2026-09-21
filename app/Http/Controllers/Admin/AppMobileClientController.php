<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppMobileClient;
use Illuminate\Http\Request;

class AppMobileClientController extends Controller
{
    public function index()
    {
        $items = AppMobileClient::ordered()->get();

        return view('admin.app-mobile-clients.index', compact('items'));
    }

    public function create()
    {
        return view('admin.app-mobile-clients.form', ['item' => new AppMobileClient()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo'] = $request->file('logo')->store('app-mobile-clients', 'public');

        AppMobileClient::create($data);

        return redirect()->route('admin.app-mobile-clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(AppMobileClient $appMobileClient)
    {
        return view('admin.app-mobile-clients.form', ['item' => $appMobileClient]);
    }

    public function update(Request $request, AppMobileClient $appMobileClient)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('app-mobile-clients', 'public');
        }

        $appMobileClient->update($data);

        return redirect()->route('admin.app-mobile-clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(AppMobileClient $appMobileClient)
    {
        $appMobileClient->delete();

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
