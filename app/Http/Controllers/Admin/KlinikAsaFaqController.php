<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KlinikAsaFaq;
use Illuminate\Http\Request;

class KlinikAsaFaqController extends Controller
{
    public function index()
    {
        $items = KlinikAsaFaq::ordered()->get();

        return view('admin.klinik-asa-faqs.index', compact('items'));
    }

    public function create()
    {
        return view('admin.klinik-asa-faqs.form', ['item' => new KlinikAsaFaq()]);
    }

    public function store(Request $request)
    {
        KlinikAsaFaq::create($this->validated($request));

        return redirect()->route('admin.klinik-asa-faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(KlinikAsaFaq $klinikAsaFaq)
    {
        return view('admin.klinik-asa-faqs.form', ['item' => $klinikAsaFaq]);
    }

    public function update(Request $request, KlinikAsaFaq $klinikAsaFaq)
    {
        $klinikAsaFaq->update($this->validated($request));

        return redirect()->route('admin.klinik-asa-faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(KlinikAsaFaq $klinikAsaFaq)
    {
        $klinikAsaFaq->delete();

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
