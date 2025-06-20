<?php

namespace App\Http\Middleware;

use App\Facades\ResponseApi;
use Closure;
use Illuminate\Http\Request;

class UserIsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return ResponseApi::setMessage('Unauthenticated.')
                ->setCode(401)
                ->response();
        }

        return $next($request);
    }
}
