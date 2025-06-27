<?php

namespace App\Http\Middleware;

use App\Facades\ResponseApi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserIsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            Log::warning("Attempted unauthorized access to the admin route.", [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'path' => $request->path(),
            ]);

            return ResponseApi::setMessage("You don't have permission to perform this action.")
                ->setCode(403)
                ->response();
        }

        return $next($request);
    }
}
