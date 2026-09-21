<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteClient;
use Illuminate\Http\Request;

class WebsiteClientController extends Controller
{
    public function index()
    {
        $items = WebsiteClient::ordered()->get();

        return view('admin.website-clients.index', compact('items'));
    }

    public function create()
    {
        return view('admin.website-clients.form', ['item' => new WebsiteClient()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo'] = $request->file('logo')->store('website-clients', 'public');

        WebsiteClient::create($data);

        return redirect()->route('admin.website-clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(WebsiteClient $websiteClient)
    {
        return view('admin.website-clients.form', ['item' => $websiteClient]);
    }

    public function update(Request $request, WebsiteClient $websiteClient)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('website-clients', 'public');
        }

        $websiteClient->update($data);

        return redirect()->route('admin.website-clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(WebsiteClient $websiteClient)
    {
        $websiteClient->delete();

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
