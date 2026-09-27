<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $items = Post::latestFirst()->get();

        return view('admin.posts.index', compact('items'));
    }

    public function create()
    {
        return view('admin.posts.form', ['item' => new Post()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['slug'] = $request->filled('slug')
            ? Post::makeUniqueSlug($request->slug)
            : Post::makeUniqueSlug($data['title']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('posts', 'public');
        }

        if ($data['is_active'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', ['item' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request);

        if ($request->filled('slug') && $request->slug !== $post->slug) {
            $data['slug'] = Post::makeUniqueSlug($request->slug, $post->id);
        } else {
            unset($data['slug']);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('posts', 'public');
        }

        if ($data['is_active'] && empty($post->published_at) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            // Meta description WAJIB — jangan dihilangkan, dipakai untuk SEO tag <meta name="description">.
            'meta_description' => ['required', 'string', 'max:160'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['author'] = $data['author'] !== null && trim($data['author']) !== '' ? trim($data['author']) : 'Admin';
        unset($data['thumbnail']);

        // Isi artikel ditulis lewat rich text editor (Quill) -> bersihkan HTML-nya
        // di sini juga (bukan cuma saat render) supaya yang tersimpan di database
        // sudah aman dari tag/atribut berbahaya, bukan cuma disaring saat tampil.
        $data['content'] = HtmlSanitizer::clean($data['content']);

        return $data;
    }
}
