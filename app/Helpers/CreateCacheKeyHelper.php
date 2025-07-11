<?php

namespace App\Helpers;

use App\Dto\Filter\FiltersDto;

final class CreateCacheKeyHelper
{
    public static function forIndex(string $key, string $model, FiltersDto $filtersDTO): string
    {
        return $key . $model . '.index.' . md5(serialize($filtersDTO));
    }

    public static function forFind(string $key, string $model, string $id, FiltersDto $filtersDTO): ?string
    {
        return $key . $model . '.show.' . $id . '.' . md5(serialize($filtersDTO));
    }
}
