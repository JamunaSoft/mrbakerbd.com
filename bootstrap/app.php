<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admins.php'));


//            Route::middleware('api')->prefix('api')->group(base_path('routes/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\Http\Middleware\SiteSettings::class,
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/success',
            '/cancel',
            '/fail',
            '/ipn',
            '/pay-via-ajax', // only required to run example codes. Please see bellow.
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
