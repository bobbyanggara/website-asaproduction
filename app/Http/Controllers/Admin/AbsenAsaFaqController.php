<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenAsaFaq;
use Illuminate\Http\Request;

class AbsenAsaFaqController extends Controller
{
    public function index()
    {
        $items = AbsenAsaFaq::ordered()->get();

        return view('admin.absen-asa-faqs.index', compact('items'));
    }

    public function create()
    {
        return view('admin.absen-asa-faqs.form', ['item' => new AbsenAsaFaq()]);
    }

    public function store(Request $request)
    {
        AbsenAsaFaq::create($this->validated($request));

        return redirect()->route('admin.absen-asa-faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(AbsenAsaFaq $absenAsaFaq)
    {
        return view('admin.absen-asa-faqs.form', ['item' => $absenAsaFaq]);
    }

    public function update(Request $request, AbsenAsaFaq $absenAsaFaq)
    {
        $absenAsaFaq->update($this->validated($request));

        return redirect()->route('admin.absen-asa-faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(AbsenAsaFaq $absenAsaFaq)
    {
        $absenAsaFaq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
