<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\VerifyN8nToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // Grup "api" (prefix /api, stateless, tanpa CSRF) diaktifkan khusus
        // untuk endpoint otomatisasi n8n di routes/api.php.
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => IsAdmin::class,
            'n8n.token' => VerifyN8nToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
