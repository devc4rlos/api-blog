<?php

namespace App\Providers;

use App\Http\Builder\ResponseBuilderInterface;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Response::macro('api', function (ResponseBuilderInterface $responseBuilder) {
            return response()->json(
                $responseBuilder->getDataResponse(),
                $responseBuilder->getCode(),
            );
        });
    }
}
