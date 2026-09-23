<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PicksFromGlobalPortfolio;
use App\Http\Controllers\Controller;
use App\Models\AppMobilePortfolio;
use Illuminate\Http\Request;

class AppMobilePortfolioController extends Controller
{
    use PicksFromGlobalPortfolio;

    public function index()
    {
        $items = AppMobilePortfolio::ordered()->get();

        return view('admin.app-mobile-portfolios.index', compact('items'));
    }

    public function create()
    {
        return view('admin.app-mobile-portfolios.form', ['item' => new AppMobilePortfolio()] + $this->pickerData(AppMobilePortfolio::class));
    }

    public function store(Request $request)
    {
        // Mode "pilih dari Portofolio": salin item global, tanpa upload.
        if ($request->filled('portfolio_ids')) {
            $count = $this->importGlobalPortfolios(AppMobilePortfolio::class, $request);

            return redirect()->route('admin.app-mobile-portfolios.index')->with(
                'success',
                $count ? "$count portofolio berhasil ditambahkan dari Portofolio." : 'Item yang dipilih sudah ada di halaman ini.'
            );
        }

        $data = $this->validated($request, true);

        $data['thumbnail'] = $request->file('thumbnail')->store('app-mobile-portfolios', 'public');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('app-mobile-portfolios', 'public');
        } else {
            $data['image'] = $data['thumbnail'];
        }

        AppMobilePortfolio::create($data);

        return redirect()->route('admin.app-mobile-portfolios.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function edit(AppMobilePortfolio $appMobilePortfolio)
    {
        return view('admin.app-mobile-portfolios.form', ['item' => $appMobilePortfolio]);
    }

    public function update(Request $request, AppMobilePortfolio $appMobilePortfolio)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('app-mobile-portfolios', 'public');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('app-mobile-portfolios', 'public');
        }

        $appMobilePortfolio->update($data);

        return redirect()->route('admin.app-mobile-portfolios.index')->with('success', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(AppMobilePortfolio $appMobilePortfolio)
    {
        $appMobilePortfolio->delete();

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
