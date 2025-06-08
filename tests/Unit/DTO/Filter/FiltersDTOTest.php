<?php

namespace Tests\Unit\DTO\Filter;

use App\DTO\Filter\FiltersDTO;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FiltersDTOTest extends TestCase
{
    public static function provideFilters(): array
    {
        return [
            [['search', null, null, null], 'search', 'search'],
            [[null, 'sortBy', null, null], 'sortBy', 'sortBy'],
            [[null, null, 'sortDirection', null], 'sortDirection', 'sortDirection'],
            [[null, null, null, 'relationships'], 'relationships', 'relationships'],
        ];
    }

    #[DataProvider('provideFilters')]
    public function test_should_add_filter(array $parameters, string $expected, string $methodGetter)
    {
        $filter = new FiltersDTO(...$parameters);
        $this->assertSame($expected, $filter->{$methodGetter}());
    }
}
