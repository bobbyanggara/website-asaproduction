<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::ordered()->withCount(['benefits', 'clients', 'faqs'])->get();

        return view('admin.products.index', compact('items'));
    }

    public function create()
    {
        return view('admin.products.form', ['item' => new Product(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Product::uniqueSlug($data['slug'] ?: $data['name']);
        $data = $this->handleUploads($request, $data);

        $product = Product::create($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Produk "'.$product->name.'" berhasil dibuat dan sudah tampil di navbar. Lengkapi Keunggulan, Klien, dan FAQ-nya di bawah.');
    }

    public function edit(Product $product)
    {
        $product->loadCount(['benefits', 'clients', 'faqs']);

        return view('admin.products.form', ['item' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);

        // Slug kosong saat EDIT = pertahankan slug yang ada. Jangan diturunkan
        // ulang dari nama, karena itu diam-diam mengubah URL halaman (dan
        // memutus link lama) hanya karena admin mengganti nama produk.
        $data['slug'] = filled($data['slug'] ?? null)
            ? Product::uniqueSlug($data['slug'], $product->id)
            : $product->slug;

        $data = $this->handleUploads($request, $data, $product);

        $product->update($data);

        return back()->with('success', 'Produk berhasil disimpan.');
    }

    public function destroy(Product $product)
    {
        // File upload milik produk ikut dibersihkan supaya storage tidak
        // menumpuk sampah. Benefit/klien/FAQ terhapus otomatis lewat FK cascade.
        foreach (['hero_image', 'preview_media'] as $field) {
            if ($product->{$field}) {
                Storage::disk('public')->delete($product->{$field});
            }
        }
        foreach ($product->clients as $client) {
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ],
            'nav_icon' => ['nullable', 'string', 'max:60', 'regex:/^fa-[a-z0-9-]+$/'],
            'nav_description' => ['nullable', 'string', 'max:255'],
            'nav_description_en' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'preview_title' => ['nullable', 'string', 'max:255'],
            'preview_caption' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'order_index' => ['nullable', 'integer'],
            'hero_image' => ['nullable', 'image', 'max:2048'],
            'preview_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm', 'max:20480'],
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung (contoh: kasir-asa).',
            'slug.unique' => 'Slug ini sudah dipakai produk lain.',
            'nav_icon.regex' => 'Ikon harus berupa kelas font-awesome, contoh: fa-cube.',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order_index'] = $data['order_index'] ?? 0;

        return $data;
    }

    /** Simpan file upload; kalau tidak ada upload baru, jangan timpa nilai lama. */
    private function handleUploads(Request $request, array $data, ?Product $product = null): array
    {
        foreach (['hero_image', 'preview_media'] as $field) {
            if ($request->hasFile($field)) {
                if ($product && $product->{$field}) {
                    Storage::disk('public')->delete($product->{$field});
                }
                $data[$field] = $request->file($field)->store('products', 'public');
            } else {
                unset($data[$field]);
            }
        }

        return $data;
    }
}
