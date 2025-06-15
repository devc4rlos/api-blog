<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LoggingMiddleware
{
    private int $slowRequestMs;

    public function __construct()
    {
        $this->slowRequestMs = config('logging.slow_request_ms', 2000);
    }

    public function handle(Request $request, Closure $next)
    {
        $requestId = $this->getRequestId($request);
        $request->headers->set('X-Request-ID', $requestId);

        Log::withContext([
            'request_id' => $requestId,
        ]);

        Log::info('Request received', $this->getBaseContext($request));
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : $request->server('REQUEST_TIME_FLOAT');
        $duration = floor((microtime(true) - $startTime) * 1000);

        if ($duration > $this->slowRequestMs) {
            Log::warning('Slow Request', $this->getSlowRequestContext($request, $response, $duration));
        } else {
            $this->logFinishedRequest($request, $response, $duration);
        }
    }

    private function getRequestId(Request $request): string
    {
        return $request->header('X-Request-ID') ?: (string) Str::uuid();
    }

    private function getBaseContext(Request $request): array
    {
        return [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'route' => $request->route()?->getName(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user()?->id,
        ];
    }

    private function getFinishedRequestContext(Request $request, Response $response, int $duration): array
    {
        return [
            'request_id' => $this->getRequestId($request),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
        ];
    }

    private function getSlowRequestContext(Request $request, Response $response, int $duration): array
    {
        return [
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user()?->id,
        ];
    }

    private function logFinishedRequest(Request $request, Response $response, int $duration): void
    {
        $context = $this->getFinishedRequestContext($request, $response, $duration);

        if ($response->isSuccessful()) {
            Log::info('Request finished', $context);
        } elseif ($response->isClientError()) {
            Log::warning('Request finished with client error', $context);
        } else {
            Log::error('Request finished with server error', $context);
        }
    }
}
