<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppendRateLimitInfoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$response instanceof JsonResponse) {
            return $response;
        }

        $data = $response->getData(true);
        $data = $this->addInformationRateLimitData($response, $data);

        return $response->setData($data);
    }

    private function addInformationRateLimitData(JsonResponse $response, array $data): array
    {
        $data = $this->addRateLimitData($response, $data);
        return $this->addRateLimitRemainingData($response, $data);
    }

    private function addRateLimitData(JsonResponse $response, array $data): array
    {
        $limit = $response->headers->get('X-RateLimit-Limit');
        if (!is_null($limit)) {
            $data['rate_limit']['limit'] = (int) $limit;
        }
        return $data;
    }

    private function addRateLimitRemainingData(JsonResponse $response, array $data): array
    {
        $remaining = $response->headers->get('X-RateLimit-Remaining');
        if (!is_null($remaining)) {
            $data['rate_limit']['remaining'] = (int) $remaining;
        }
        return $data;
    }
}
