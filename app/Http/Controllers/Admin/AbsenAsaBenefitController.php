<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenAsaBenefit;
use Illuminate\Http\Request;

class AbsenAsaBenefitController extends Controller
{
    public function index()
    {
        $items = AbsenAsaBenefit::ordered()->get();

        return view('admin.absen-asa-benefits.index', compact('items'));
    }

    public function create()
    {
        return view('admin.absen-asa-benefits.form', ['item' => new AbsenAsaBenefit()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('absen-asa-benefits', 'public');
        }

        AbsenAsaBenefit::create($data);

        return redirect()->route('admin.absen-asa-benefits.index')->with('success', 'Poin keunggulan berhasil ditambahkan.');
    }

    public function edit(AbsenAsaBenefit $absenAsaBenefit)
    {
        return view('admin.absen-asa-benefits.form', ['item' => $absenAsaBenefit]);
    }

    public function update(Request $request, AbsenAsaBenefit $absenAsaBenefit)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('absen-asa-benefits', 'public');
        } elseif ($data['icon'] === null || $data['icon'] === '') {
            unset($data['icon']);
        }

        $absenAsaBenefit->update($data);

        return redirect()->route('admin.absen-asa-benefits.index')->with('success', 'Poin keunggulan berhasil diperbarui.');
    }

    public function destroy(AbsenAsaBenefit $absenAsaBenefit)
    {
        $absenAsaBenefit->delete();

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
