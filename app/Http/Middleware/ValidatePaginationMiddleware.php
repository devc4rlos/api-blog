<?php

namespace App\Http\Middleware;

use App\Facades\ResponseApi;
use Closure;
use Illuminate\Http\Request;

class ValidatePaginationMiddleware
{
    public function handle(Request $request, Closure $next, string $modelClass = '')
    {
        if (!class_exists($modelClass)) {
            return ResponseApi::setMessage('Server configuration error')
                ->setCode(500)
                ->response();
        }

        $page = (int) $request->query('page', 1);

        if ($page <= 1) {
            return $next($request);
        }

        $total = $modelClass::count();
        $perPage = 15;
        $lastPage = ceil($total / $perPage);

        if ($page > $lastPage && $total > 0) {
            return ResponseApi::setMessage("Requested page ($page) is out of range. The last page is $lastPage.")
                ->setCode(404)
                ->response();
        }

        return $next($request);
    }
}
