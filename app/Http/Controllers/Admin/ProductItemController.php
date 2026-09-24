<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBenefit;
use App\Models\ProductClient;
use App\Models\ProductFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD generik untuk item milik sebuah produk: Keunggulan, Klien, dan FAQ.
 *
 * Sebelumnya ini 3 controller x jumlah produk (Absen ASA, Klinik ASA, ...).
 * Sekarang satu controller dengan parameter {type} (benefits|clients|faqs)
 * dan {product}, jadi produk baru otomatis punya ketiganya tanpa kode baru.
 *
 * Route: admin/products/{product}/{type}[/{id}]
 */
class ProductItemController extends Controller
{
    /** Konfigurasi per tipe: model, folder upload, label, dan aturan validasi. */
    private const TYPES = [
        'benefits' => [
            'model' => ProductBenefit::class,
            'label' => 'Keunggulan',
            'singular' => 'Poin keunggulan',
        ],
        'clients' => [
            'model' => ProductClient::class,
            'label' => 'Klien',
            'singular' => 'Klien',
        ],
        'faqs' => [
            'model' => ProductFaq::class,
            'label' => 'FAQ',
            'singular' => 'FAQ',
        ],
    ];

    public function index(Product $product, string $type)
    {
        $cfg = $this->config($type);
        $items = $product->{$type}()->get();

        return view("admin.product-items.$type.index", [
            'product' => $product, 'items' => $items, 'type' => $type, 'label' => $cfg['label'],
        ]);
    }

    public function create(Product $product, string $type)
    {
        $cfg = $this->config($type);

        return view("admin.product-items.$type.form", [
            'product' => $product, 'type' => $type, 'label' => $cfg['label'],
            'item' => new $cfg['model'](['is_active' => true]),
        ]);
    }

    public function store(Request $request, Product $product, string $type)
    {
        $cfg = $this->config($type);
        $data = $this->validated($request, $type, true);
        $data = $this->handleFiles($request, $type, $data);

        $product->{$type}()->create($data);

        return redirect()->route('admin.product-items.index', [$product, $type])
            ->with('success', $cfg['singular'].' berhasil ditambahkan.');
    }

    public function edit(Product $product, string $type, int $id)
    {
        $cfg = $this->config($type);

        return view("admin.product-items.$type.form", [
            'product' => $product, 'type' => $type, 'label' => $cfg['label'],
            'item' => $this->findItem($product, $type, $id),
        ]);
    }

    public function update(Request $request, Product $product, string $type, int $id)
    {
        $cfg = $this->config($type);
        $item = $this->findItem($product, $type, $id);

        $data = $this->validated($request, $type, false);
        $data = $this->handleFiles($request, $type, $data, $item);

        $item->update($data);

        return redirect()->route('admin.product-items.index', [$product, $type])
            ->with('success', $cfg['singular'].' berhasil diperbarui.');
    }

    public function destroy(Product $product, string $type, int $id)
    {
        $cfg = $this->config($type);
        $item = $this->findItem($product, $type, $id);

        // Bersihkan file upload milik item ini (logo klien / ikon keunggulan).
        foreach (['logo', 'icon'] as $field) {
            if (! empty($item->{$field}) && str_contains($item->{$field}, '/')) {
                Storage::disk('public')->delete($item->{$field});
            }
        }

        $item->delete();

        return back()->with('success', $cfg['singular'].' berhasil dihapus.');
    }

    /** Ambil konfigurasi tipe, atau 404 kalau {type} di URL tidak dikenal. */
    private function config(string $type): array
    {
        abort_unless(isset(self::TYPES[$type]), 404);

        return self::TYPES[$type];
    }

    /**
     * Cari item HANYA di dalam produk ini. Ini mencegah admin mengedit/menghapus
     * item milik produk lain lewat URL yang diakali (mis. /products/1/faqs/99
     * padahal FAQ 99 milik produk 2).
     */
    private function findItem(Product $product, string $type, int $id)
    {
        $this->config($type);

        return $product->{$type}()->whereKey($id)->firstOrFail();
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
            'faqs' => [
                'question' => ['required', 'string', 'max:255'],
                'answer' => ['required', 'string'],
            ],
        };

        $data = $request->validate($rules + $common);

        $data['is_active'] = $request->boolean('is_active');
        $data['order_index'] = $data['order_index'] ?? 0;

        // Field file bukan kolom database; ditangani terpisah di handleFiles().
        unset($data['icon_file'], $data['logo']);

        return $data;
    }

    private function handleFiles(Request $request, string $type, array $data, $item = null): array
    {
        if ($type === 'clients' && $request->hasFile('logo')) {
            if ($item && $item->logo) {
                Storage::disk('public')->delete($item->logo);
            }
            $data['logo'] = $request->file('logo')->store('product-clients', 'public');
        }

        if ($type === 'benefits') {
            if ($request->hasFile('icon_file')) {
                if ($item && $item->icon && str_contains($item->icon, '/')) {
                    Storage::disk('public')->delete($item->icon);
                }
                $data['icon'] = $request->file('icon_file')->store('product-benefits', 'public');
            } elseif (($data['icon'] ?? null) === null || $data['icon'] === '') {
                // Field ikon dikosongkan & tidak ada upload: pertahankan ikon lama.
                unset($data['icon']);
            }
        }

        return $data;
    }
}
