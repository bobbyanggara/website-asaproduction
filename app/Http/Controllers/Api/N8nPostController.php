<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Endpoint yang dipanggil n8n (HTTP Request node) untuk menambah artikel
 * blog secara otomatis. Ditaruh terpisah dari Admin\PostController karena
 * beda cara autentikasi (token statis, bukan login sesi admin) dan beda
 * bentuk input (JSON dari n8n, thumbnail berupa URL bukan file upload).
 */
class N8nPostController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            // Tetap wajib, sama seperti form admin — dipakai untuk meta description SEO.
            'meta_description' => ['required', 'string', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            // Kirim sebagai URL gambar (mis. dari node image-gen/Unsplash di n8n),
            // BUKAN file upload — jauh lebih gampang dibangun di HTTP Request node
            // n8n daripada multipart/binary.
            'thumbnail_url' => ['nullable', 'url'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['author'] = filled($data['author'] ?? null) ? trim($data['author']) : 'Admin';
        $data['slug'] = filled($data['slug'] ?? null)
            ? Post::makeUniqueSlug($data['slug'])
            : Post::makeUniqueSlug($data['title']);

        if ($data['is_active'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($thumbUrl = $request->input('thumbnail_url')) {
            $data['thumbnail'] = $this->downloadThumbnail($thumbUrl);
        }
        unset($data['thumbnail_url']);

        $post = Post::create($data);

        // AutoTranslates (trait di Post) cuma otomatis jalan untuk request ke
        // /admin/* (lihat isAdminWrite() di app/Models/Concerns/AutoTranslates.php).
        // Endpoint ini di luar /admin/*, jadi terjemahan ID->EN dipicu manual di sini
        // supaya versi Inggris artikel tetap terisi walau dibuat dari n8n.
        $post->translateNow();

        return response()->json([
            'id' => $post->id,
            'slug' => $post->slug,
            'url' => route('blog.show', $post->slug),
        ], 201);
    }

    /** Unduh gambar dari URL lalu simpan ke storage/app/public/posts, sama seperti upload manual di admin. */
    private function downloadThumbnail(string $url): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);

            if (! $response->successful()) {
                Log::warning('N8nPostController: unduh thumbnail gagal (bukan 2xx)', ['url' => $url, 'status' => $response->status()]);

                return null;
            }

            $mime = $response->header('Content-Type', 'image/jpeg');
            $ext = match (true) {
                str_contains($mime, 'png') => 'png',
                str_contains($mime, 'webp') => 'webp',
                default => 'jpg',
            };

            $path = 'posts/'.Str::random(40).'.'.$ext;
            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Throwable $e) {
            // Thumbnail gagal BUKAN alasan gagalkan seluruh artikel — tetap lanjut tanpa gambar.
            Log::error('N8nPostController: exception saat unduh thumbnail', ['url' => $url, 'message' => $e->getMessage()]);

            return null;
        }
    }
}
