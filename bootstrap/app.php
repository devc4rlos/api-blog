<?php

use App\Facades\ResponseApi;
use App\Http\Middleware\AppendRateLimitInfoMiddleware;
use App\Http\Middleware\ForceApplicationJsonMiddleware;
use App\Http\Middleware\LoggingMiddleware;
use App\Http\Middleware\UserIsAdminMiddleware;
use App\Http\Middleware\ValidatePaginationMiddleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            AppendRateLimitInfoMiddleware::class,
            ForceApplicationJsonMiddleware::class,
            LoggingMiddleware::class,
        ]);

        $middleware->alias([
            'validate.pagination' => ValidatePaginationMiddleware::class,
            'auth.admin' => UserIsAdminMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ThrottleRequestsException $e):?JsonResponse {
            return ResponseApi::setMessage($e->getMessage())->setCode(429)->response();
        });

        $exceptions->render(function (NotFoundHttpException $e):?JsonResponse {
            if ($e->getPrevious() instanceof ModelNotFoundException) {
                return ResponseApi::setMessage('The resource could not be found')->setCode(404)->response();
            }
            return ResponseApi::setMessage($e->getMessage())->setCode(404)->response();
        });
        $exceptions->level(PDOException::class, LogLevel::CRITICAL);
    })->create();
