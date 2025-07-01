<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitingApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $rateLimitAdmin = config('rate-limiting.admin');
            $rateLimitUser = config('rate-limiting.user');
            $rateLimitVisitor = config('rate-limiting.visitor');

            if ($request->user() && $request->user()->isAdmin()) {
                return $rateLimitAdmin === null ? Limit::none() : Limit::perMinute($rateLimitAdmin)->by($request->user()->id);
            }

            return $request->user()
                ? Limit::perMinute($rateLimitUser)->by($request->user()->id)
                : Limit::perMinute($rateLimitVisitor)->by($request->ip());
        });
    }
}
