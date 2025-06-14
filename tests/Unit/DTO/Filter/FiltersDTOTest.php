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
        $filter = new FiltersDTO(...$parameters);
        $this->assertSame($expected, $filter->{$methodGetter}());
    }
}
