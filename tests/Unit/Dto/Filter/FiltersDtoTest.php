<?php

namespace Tests\Unit\Dto\Filter;

use App\Dto\Filter\FiltersDto;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FiltersDtoTest extends TestCase
{
    public static function provideFilters(): array
    {
        return [
            [['search'], 'search', 'search'],
            [[null, 'sortBy'], 'sortBy', 'sortBy'],
            [[null, null, 'sortDirection'], 'sortDirection', 'sortDirection'],
            [[null, null, null, 'relationships'], 'relationships', 'relationships'],
            [[null, null, null, null, 'page'], 'page', 'page'],
        ];
    }

    #[DataProvider('provideFilters')]
    public function test_should_add_filter(array $parameters, string $expected, string $methodGetter)
    {
        $filter = new FiltersDto(...$parameters);
        $this->assertSame($expected, $filter->{$methodGetter}());
    }
}
