<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // Arahkan pengguna yang belum login ke /login
        $middleware->redirectGuestsTo('/login');

        // Arahkan pengguna yang sudah login saat mencoba mengakses halaman guest (seperti /login atau /register)
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->user()?->role === 'admin') {
                return route('admin.index'); // Atau sesuaikan dengan nama route dashboard admin Anda
            }

            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
