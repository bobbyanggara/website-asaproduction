<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomSystemBenefit;
use Illuminate\Http\Request;

class CustomSystemBenefitController extends Controller
{
    public function index()
    {
        $items = CustomSystemBenefit::ordered()->get();

        return view('admin.custom-system-benefits.index', compact('items'));
    }

    public function create()
    {
        return view('admin.custom-system-benefits.form', ['item' => new CustomSystemBenefit()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('custom-system-benefits', 'public');
        }

        CustomSystemBenefit::create($data);

        return redirect()->route('admin.custom-system-benefits.index')->with('success', 'Poin keunggulan berhasil ditambahkan.');
    }

    public function edit(CustomSystemBenefit $customSystemBenefit)
    {
        return view('admin.custom-system-benefits.form', ['item' => $customSystemBenefit]);
    }

    public function update(Request $request, CustomSystemBenefit $customSystemBenefit)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('custom-system-benefits', 'public');
        } elseif ($data['icon'] === null || $data['icon'] === '') {
            unset($data['icon']);
        }

        $customSystemBenefit->update($data);

        return redirect()->route('admin.custom-system-benefits.index')->with('success', 'Poin keunggulan berhasil diperbarui.');
    }

    public function destroy(CustomSystemBenefit $customSystemBenefit)
    {
        $customSystemBenefit->delete();

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
