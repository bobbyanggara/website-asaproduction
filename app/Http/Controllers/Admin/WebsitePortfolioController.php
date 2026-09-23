<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PicksFromGlobalPortfolio;
use App\Http\Controllers\Controller;
use App\Models\WebsitePortfolio;
use Illuminate\Http\Request;

class WebsitePortfolioController extends Controller
{
    use PicksFromGlobalPortfolio;

    public function index()
    {
        $items = WebsitePortfolio::ordered()->get();

        return view('admin.website-portfolios.index', compact('items'));
    }

    public function create()
    {
        return view('admin.website-portfolios.form', ['item' => new WebsitePortfolio()] + $this->pickerData(WebsitePortfolio::class));
    }

    public function store(Request $request)
    {
        // Mode "pilih dari Portofolio": salin item global, tanpa upload.
        if ($request->filled('portfolio_ids')) {
            $count = $this->importGlobalPortfolios(WebsitePortfolio::class, $request);

            return redirect()->route('admin.website-portfolios.index')->with(
                'success',
                $count ? "$count portofolio berhasil ditambahkan dari Portofolio." : 'Item yang dipilih sudah ada di halaman ini.'
            );
        }

        $data = $this->validated($request, true);

        $data['thumbnail'] = $request->file('thumbnail')->store('website-portfolios', 'public');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('website-portfolios', 'public');
        } else {
            $data['image'] = $data['thumbnail'];
        }

        WebsitePortfolio::create($data);

        return redirect()->route('admin.website-portfolios.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function edit(WebsitePortfolio $websitePortfolio)
    {
        return view('admin.website-portfolios.form', ['item' => $websitePortfolio]);
    }

    public function update(Request $request, WebsitePortfolio $websitePortfolio)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('website-portfolios', 'public');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('website-portfolios', 'public');
        }

        $websitePortfolio->update($data);

        return redirect()->route('admin.website-portfolios.index')->with('success', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(WebsitePortfolio $websitePortfolio)
    {
        $websitePortfolio->delete();

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
