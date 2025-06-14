<?php

namespace App\Helpers;

use App\DTO\Filter\FiltersDTO;

final class CreateCacheKeyHelper
{
    public static function forIndex(string $model, FiltersDTO $filtersDTO): string
    {
        return $model . '.index.' . md5(serialize($filtersDTO));
    }

    public static function forFind(string $model, int $id, FiltersDTO $filtersDTO): ?string
    {
        return $model . '.show.' . $id . '.' . md5(serialize($filtersDTO));
    }
}
