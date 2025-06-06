<?php

namespace App\Facades;

use App\Http\Builder\ResponseBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * @see ResponseBuilder
 */
class ResponseApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ResponseBuilder::class;
    }
}
