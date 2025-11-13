<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Foundation\Application;
// ✅ Tambahkan baris ini
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ Daftarkan middleware admin dan superadmin di sini
        $middleware->alias([
            'admin' => IsAdmin::class,
            'superadmin' => SuperAdminMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
