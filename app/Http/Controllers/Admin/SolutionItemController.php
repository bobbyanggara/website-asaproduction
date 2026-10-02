<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Solution;
use App\Models\SolutionBenefit;
use App\Models\SolutionClient;
use App\Models\SolutionFaq;
use App\Models\SolutionPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD generik untuk item milik sebuah halaman solusi: Keunggulan, Klien,
 * Portofolio, dan FAQ.
 *
 * Sebelumnya ini 4 controller x 3 halaman solusi. Sekarang satu controller
 * dengan parameter {type} (benefits|clients|portfolios|faqs) dan {solution},
 * jadi halaman solusi baru otomatis punya keempatnya tanpa kode baru.
 *
 * Route: admin/solutions/{solution}/{type}[/{id}]
 */
class SolutionItemController extends Controller
{
    private const TYPES = [
        'benefits' => ['model' => SolutionBenefit::class, 'label' => 'Keunggulan', 'singular' => 'Poin keunggulan'],
        'clients' => ['model' => SolutionClient::class, 'label' => 'Klien', 'singular' => 'Klien'],
        'portfolios' => ['model' => SolutionPortfolio::class, 'label' => 'Portofolio', 'singular' => 'Portofolio'],
        'faqs' => ['model' => SolutionFaq::class, 'label' => 'FAQ', 'singular' => 'FAQ'],
    ];

    public function index(Solution $solution, string $type)
    {
        $cfg = $this->config($type);

        return view("admin.solution-items.$type.index", [
            'solution' => $solution, 'items' => $solution->{$type}()->get(), 'type' => $type, 'label' => $cfg['label'],
        ]);
    }

    public function create(Solution $solution, string $type)
    {
        $cfg = $this->config($type);

        $extra = $type === 'portfolios'
            ? [
                'globalPortfolios' => Portfolio::active()->ordered()->get(),
                'usedThumbnails' => $solution->portfolios()->pluck('thumbnail')->all(),
            ]
            : [];

        return view("admin.solution-items.$type.form", [
            'solution' => $solution, 'type' => $type, 'label' => $cfg['label'],
            'item' => new $cfg['model'](['is_active' => true]),
        ] + $extra);
    }

    public function store(Request $request, Solution $solution, string $type)
    {
        $cfg = $this->config($type);

        // Portofolio, mode "pilih dari Portofolio": salin item global, tanpa upload.
        if ($type === 'portfolios' && $request->filled('portfolio_ids')) {
            $count = $this->importGlobalPortfolios($solution, $request);

            return redirect()->route('admin.solution-items.index', [$solution, $type])->with(
                'success',
                $count ? "$count portofolio berhasil ditambahkan dari Portofolio." : 'Item yang dipilih sudah ada di halaman ini.'
            );
        }

        $data = $this->validated($request, $type, true);
        $data = $this->handleFiles($request, $type, $data);

        $solution->{$type}()->create($data);

        return redirect()->route('admin.solution-items.index', [$solution, $type])
            ->with('success', $cfg['singular'].' berhasil ditambahkan.');
    }

    public function edit(Solution $solution, string $type, int $id)
    {
        $cfg = $this->config($type);

        return view("admin.solution-items.$type.form", [
            'solution' => $solution, 'type' => $type, 'label' => $cfg['label'],
            'item' => $this->findItem($solution, $type, $id),
        ]);
    }

    public function update(Request $request, Solution $solution, string $type, int $id)
    {
        $cfg = $this->config($type);
        $item = $this->findItem($solution, $type, $id);

        $data = $this->validated($request, $type, false);
        $data = $this->handleFiles($request, $type, $data, $item);

        $item->update($data);

        return redirect()->route('admin.solution-items.index', [$solution, $type])
            ->with('success', $cfg['singular'].' berhasil diperbarui.');
    }

    public function destroy(Solution $solution, string $type, int $id)
    {
        $cfg = $this->config($type);
        $item = $this->findItem($solution, $type, $id);

        // Bersihkan file upload milik item ini (logo klien / ikon keunggulan).
        // Gambar portofolio tidak dihapus: bisa dipakai bersama Portofolio global.
        foreach (['logo', 'icon'] as $field) {
            if (! empty($item->{$field}) && str_contains($item->{$field}, '/')) {
                Storage::disk('public')->delete($item->{$field});
            }
        }

        $item->delete();

        return back()->with('success', $cfg['singular'].' berhasil dihapus.');
    }

    private function config(string $type): array
    {
        abort_unless(isset(self::TYPES[$type]), 404);

        return self::TYPES[$type];
    }

    /**
     * Cari item HANYA di dalam halaman solusi ini. Mencegah admin mengedit/menghapus
     * item milik halaman lain lewat URL yang diakali.
     */
    private function findItem(Solution $solution, string $type, int $id)
    {
        $this->config($type);

        return $solution->{$type}()->whereKey($id)->firstOrFail();
    }

    private function validated(Request $request, string $type, bool $creating): array
    {
        $common = [
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];

        $rules = match ($type) {
            'benefits' => [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'icon' => ['nullable', 'string', 'max:255'],
                'icon_file' => ['nullable', 'image', 'max:1024'],
            ],
            'clients' => [
                'name' => ['nullable', 'string', 'max:255'],
                'logo' => [$creating ? 'required' : 'nullable', 'image', 'max:2048'],
            ],
            'portfolios' => [
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:500'],
                'link' => ['nullable', 'url', 'max:255'],
                'thumbnail' => [$creating ? 'required' : 'nullable', 'image', 'max:4096'],
                'image' => ['nullable', 'image', 'max:6144'],
            ],
            'faqs' => [
                'question' => ['required', 'string', 'max:255'],
                'answer' => ['required', 'string'],
            ],
        };

        $data = $request->validate($rules + $common);

        $data['is_active'] = $request->boolean('is_active');
        $data['order_index'] = $data['order_index'] ?? 0;

        // Field file bukan kolom database; ditangani terpisah di handleFiles().
        unset($data['icon_file'], $data['logo'], $data['thumbnail'], $data['image']);

        return $data;
    }

    private function handleFiles(Request $request, string $type, array $data, $item = null): array
    {
        if ($type === 'clients' && $request->hasFile('logo')) {
            if ($item && $item->logo) {
                Storage::disk('public')->delete($item->logo);
            }
            $data['logo'] = $request->file('logo')->store('solution-clients', 'public');
        }

        if ($type === 'benefits') {
            if ($request->hasFile('icon_file')) {
                if ($item && $item->icon && str_contains($item->icon, '/')) {
                    Storage::disk('public')->delete($item->icon);
                }
                $data['icon'] = $request->file('icon_file')->store('solution-benefits', 'public');
            } elseif (($data['icon'] ?? null) === null || $data['icon'] === '') {
                // Field ikon dikosongkan & tidak ada upload: pertahankan ikon lama.
                unset($data['icon']);
            }
        }

        if ($type === 'portfolios') {
            // File lama tidak dihapus saat diganti: bisa dipakai bersama Portofolio global.
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('solution-portfolios', 'public');
            }

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('solution-portfolios', 'public');
            } elseif (! $item) {
                // Item baru tanpa gambar full-size: pakai thumbnail.
                $data['image'] = $data['thumbnail'] ?? null;
            }
        }

        return $data;
    }

    /** Salin portofolio global terpilih ke halaman ini. Return jumlah yang benar-benar ditambahkan. */
    private function importGlobalPortfolios(Solution $solution, Request $request): int
    {
        $request->validate([
            'portfolio_ids' => ['required', 'array'],
            'portfolio_ids.*' => ['integer', 'exists:portfolios,id'],
        ]);

        $used = $solution->portfolios()->pluck('thumbnail')->all();
        $nextOrder = ((int) $solution->portfolios()->max('order_index')) + 1;
        $count = 0;

        $picked = Portfolio::active()->ordered()
            ->whereIn('id', $request->input('portfolio_ids'))
            ->get();

        foreach ($picked as $portfolio) {
            if (in_array($portfolio->thumbnail, $used, true)) {
                continue; // sudah ada di halaman ini, lewati
            }

            $solution->portfolios()->create([
                'title' => $portfolio->title,
                'title_en' => $portfolio->title_en,
                'description' => $portfolio->description,
                'description_en' => $portfolio->description_en,
                'link' => $portfolio->link,
                'thumbnail' => $portfolio->thumbnail,
                'image' => $portfolio->image ?: $portfolio->thumbnail,
                'order_index' => $nextOrder++,
                'is_active' => true,
            ]);
            $count++;
        }

        return $count;
    }
}
