<?php

use App\Http\Middleware\ForceApplicationJsonMiddleware;
use App\Http\Middleware\ValidatePaginationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function() {
            Route::middleware(['api'])
                ->prefix('v1')
                ->group(base_path('routes/v1/api.php'));
        },
        commands: base_path('routes/console.php'),
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            ForceApplicationJsonMiddleware::class,
        ]);

        $middleware->alias([
            'validate.pagination' => ValidatePaginationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
