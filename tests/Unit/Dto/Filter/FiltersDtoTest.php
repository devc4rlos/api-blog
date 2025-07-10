<?php

namespace Tests\Unit\Dto\Filter;

use App\Dto\Filter\FiltersDto;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FiltersDtoTest extends TestCase
{
    public static function provideFilters(): array
    {
        $parameters = fn (?string $search = null, ?string $sortBy = null, ?string $sortDirection = null, ?string $page = null, ?string $searchBy = null) => [$search, $sortBy, $sortDirection, $page, $searchBy];

        return [
            [$parameters(search: 'search'), 'search', 'search'],
            [$parameters(sortBy: 'sortBy'), 'sortBy', 'sortBy'],
            [$parameters(sortDirection: 'sortDirection'), 'sortDirection', 'sortDirection'],
            [$parameters(page: 'page'), 'page', 'page'],
            [$parameters(searchBy: 'searchBy'), 'searchBy', 'searchBy'],
        ];
    }

    #[DataProvider('provideFilters')]
    public function test_should_add_filter(array $parameters, string $expected, string $methodGetter)
    {
        $filter = new FiltersDto(...$parameters);
        $this->assertSame($expected, $filter->{$methodGetter}());
    }
}
