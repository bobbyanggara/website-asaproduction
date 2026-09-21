<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBenefit;
use Illuminate\Http\Request;

class WebsiteBenefitController extends Controller
{
    public function index()
    {
        $items = WebsiteBenefit::ordered()->get();

        return view('admin.website-benefits.index', compact('items'));
    }

    public function create()
    {
        return view('admin.website-benefits.form', ['item' => new WebsiteBenefit()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('website-benefits', 'public');
        }

        WebsiteBenefit::create($data);

        return redirect()->route('admin.website-benefits.index')->with('success', 'Poin keunggulan berhasil ditambahkan.');
    }

    public function edit(WebsiteBenefit $websiteBenefit)
    {
        return view('admin.website-benefits.form', ['item' => $websiteBenefit]);
    }

    public function update(Request $request, WebsiteBenefit $websiteBenefit)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('website-benefits', 'public');
        } elseif ($data['icon'] === null || $data['icon'] === '') {
            unset($data['icon']);
        }

        $websiteBenefit->update($data);

        return redirect()->route('admin.website-benefits.index')->with('success', 'Poin keunggulan berhasil diperbarui.');
    }

    public function destroy(WebsiteBenefit $websiteBenefit)
    {
        $websiteBenefit->delete();

        return back()->with('success', 'Poin keunggulan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'icon' => ['nullable', 'string', 'max:255'],
            'icon_file' => ['nullable', 'image', 'max:1024'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['icon_file']);

        return $data;
    }
}
