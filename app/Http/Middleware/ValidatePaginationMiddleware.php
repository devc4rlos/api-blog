<?php

namespace App\Http\Middleware;

use App\Facades\ResponseApi;
use Closure;
use Illuminate\Http\Request;

class ValidatePaginationMiddleware
{
    public function handle(Request $request, Closure $next, int $total)
    {
        $page = (int) $request->query('page', 1);

        if ($page <= 1) {
            return $next($request);
        }

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
