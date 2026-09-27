<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autentikasi khusus untuk endpoint otomatisasi (dipanggil n8n, bukan browser).
 *
 * n8n tidak bisa login sesi seperti admin di /admin/*, jadi endpoint ini
 * memakai token statis yang dikirim n8n lewat header:
 *
 *     Authorization: Bearer <N8N_API_TOKEN>
 *
 * Token-nya disimpan di .env (N8N_API_TOKEN) dan di kredensial n8n — TIDAK
 * pernah ditaruh langsung di dalam workflow JSON. Kalau token belum di-set
 * di server, endpoint otomatis ditutup total (abort 500) daripada diam-diam
 * menerima siapa saja.
 */
class VerifyN8nToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.n8n.token');

        if (blank($expected)) {
            abort(500, 'N8N_API_TOKEN belum dikonfigurasi di server.');
        }

        $given = $request->bearerToken();

        if (! $given || ! hash_equals($expected, $given)) {
            abort(401, 'Token otomatisasi tidak valid.');
        }

        return $next($request);
    }
}
