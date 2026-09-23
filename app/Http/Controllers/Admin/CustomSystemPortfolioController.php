<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PicksFromGlobalPortfolio;
use App\Http\Controllers\Controller;
use App\Models\CustomSystemPortfolio;
use Illuminate\Http\Request;

class CustomSystemPortfolioController extends Controller
{
    use PicksFromGlobalPortfolio;

    public function index()
    {
        $items = CustomSystemPortfolio::ordered()->get();

        return view('admin.custom-system-portfolios.index', compact('items'));
    }

    public function create()
    {
        return view('admin.custom-system-portfolios.form', ['item' => new CustomSystemPortfolio()] + $this->pickerData(CustomSystemPortfolio::class));
    }

    public function store(Request $request)
    {
        // Mode "pilih dari Portofolio": salin item global, tanpa upload.
        if ($request->filled('portfolio_ids')) {
            $count = $this->importGlobalPortfolios(CustomSystemPortfolio::class, $request);

            return redirect()->route('admin.custom-system-portfolios.index')->with(
                'success',
                $count ? "$count portofolio berhasil ditambahkan dari Portofolio." : 'Item yang dipilih sudah ada di halaman ini.'
            );
        }

        $data = $this->validated($request, true);

        $data['thumbnail'] = $request->file('thumbnail')->store('custom-system-portfolios', 'public');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('custom-system-portfolios', 'public');
        } else {
            $data['image'] = $data['thumbnail'];
        }

        CustomSystemPortfolio::create($data);

        return redirect()->route('admin.custom-system-portfolios.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function edit(CustomSystemPortfolio $customSystemPortfolio)
    {
        return view('admin.custom-system-portfolios.form', ['item' => $customSystemPortfolio]);
    }

    public function update(Request $request, CustomSystemPortfolio $customSystemPortfolio)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('custom-system-portfolios', 'public');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('custom-system-portfolios', 'public');
        }

        $customSystemPortfolio->update($data);

        return redirect()->route('admin.custom-system-portfolios.index')->with('success', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(CustomSystemPortfolio $customSystemPortfolio)
    {
        $customSystemPortfolio->delete();

        return back()->with('success', 'Portofolio berhasil dihapus.');
    }

    private function validated(Request $request, bool $thumbnailRequired): array
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'link' => ['nullable', 'url', 'max:255'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'thumbnail' => [$thumbnailRequired ? 'required' : 'nullable', 'image', 'max:4096'],
            'image' => ['nullable', 'image', 'max:6144'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['thumbnail'], $data['image']);

        return $data;
    }
}
