<?php

namespace App\Helpers;

use App\Dto\Filter\FiltersDto;

final class CreateCacheKeyHelper
{
    public static function forIndex(string $model, FiltersDto $filtersDTO): string
    {
        return $model . '.index.' . md5(serialize($filtersDTO));
    }

    public static function forFind(string $model, int $id, FiltersDto $filtersDTO): ?string
    {
        return $model . '.show.' . $id . '.' . md5(serialize($filtersDTO));
    }
}
