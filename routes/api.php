<?php

use App\Http\Controllers\Api\N8nPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Beda dari routes/web.php: grup middleware "api" TIDAK punya session/cookie
| /CSRF, jadi cocok dipanggil dari luar seperti n8n (yang tidak bisa login
| sesi/browser). Keamanannya pakai token statis lewat middleware
| App\Http\Middleware\VerifyN8nToken (alias "n8n.token"), bukan cookie login.
*/

Route::post('automation/posts', [N8nPostController::class, 'store'])
    ->middleware('n8n.token')
    ->name('api.automation.posts.store');
