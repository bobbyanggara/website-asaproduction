<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KlinikAsaBenefit;
use Illuminate\Http\Request;

class KlinikAsaBenefitController extends Controller
{
    public function index()
    {
        $items = KlinikAsaBenefit::ordered()->get();

        return view('admin.klinik-asa-benefits.index', compact('items'));
    }

    public function create()
    {
        return view('admin.klinik-asa-benefits.form', ['item' => new KlinikAsaBenefit()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('klinik-asa-benefits', 'public');
        }

        KlinikAsaBenefit::create($data);

        return redirect()->route('admin.klinik-asa-benefits.index')->with('success', 'Poin keunggulan berhasil ditambahkan.');
    }

    public function edit(KlinikAsaBenefit $klinikAsaBenefit)
    {
        return view('admin.klinik-asa-benefits.form', ['item' => $klinikAsaBenefit]);
    }

    public function update(Request $request, KlinikAsaBenefit $klinikAsaBenefit)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('klinik-asa-benefits', 'public');
        } elseif ($data['icon'] === null || $data['icon'] === '') {
            unset($data['icon']);
        }

        $klinikAsaBenefit->update($data);

        return redirect()->route('admin.klinik-asa-benefits.index')->with('success', 'Poin keunggulan berhasil diperbarui.');
    }

    public function destroy(KlinikAsaBenefit $klinikAsaBenefit)
    {
        $klinikAsaBenefit->delete();

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
