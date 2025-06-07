<?php

namespace Tests\Unit\Pagination;

use App\Http\Pagination\PaginatorLengthAwarePaginator;
use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaginatorLengthAwarePaginatorTest extends TestCase
{
    public static function provideGetterTests(): array
    {
        return [
            'total' => ['total', 'total', 15],
            'per_page' => ['perPage', 'perPage', 10],
            'current_page' => ['currentPage', 'currentPage', 10],
            'last_page' => ['lastPage', 'lastPage', 10],
            'path' => ['path', 'path', 'http://localhost'],
        ];
    }

    public static function provideGetterLinksTests(): array
    {
        return [
            'link_first' => ['url', 'linkFirst', 'http://localhost?page=1'],
            'link_last' => ['url', 'linkLast', 'http://localhost?page=10', fn(LengthAwarePaginator&MockInterface $mock) => $mock->shouldReceive('lastPage')->once()->andReturn(10)],
            'link_previous' => ['previousPageUrl', 'linkPrevious', 'http://localhost?page=1'],
            'link_next' => ['nextPageUrl', 'linkNext', 'http://localhost?page=3'],
        ];
    }

    #[DataProvider('provideGetterTests')]
    public function test_should_delegate_getter_calls_correctly(string $mockMethod, string $getterMethod, mixed $value)
    {
        $mock = Mockery::mock(LengthAwarePaginator::class);
        $mock->shouldReceive($mockMethod)->once()->andReturn($value);
        $paginator = new PaginatorLengthAwarePaginator($mock);

        $this->assertSame($value, $paginator->{$getterMethod}());
    }

    #[DataProvider('provideGetterLinksTests')]
    public function test_should_set_and_get_properties_links_correctly(string $mockMethod, string $getterMethod, mixed $value, ?Closure $closure = null)
    {
        $mock = Mockery::mock(LengthAwarePaginator::class);
        $queryParameters = ['search' => 'test'];

        $mock->shouldReceive('appends')->once()->andReturnSelf();
        $mock->shouldReceive($mockMethod)->once()->andReturn($value);

        if ($closure) {
            $closure($mock);
        }

        $paginator = new PaginatorLengthAwarePaginator($mock, $queryParameters);

        $this->assertSame($value, $paginator->{$getterMethod}());
    }

    public function test_should_return_links()
    {
        $first = 'http://localhost?page=1';
        $last = 'http://localhost?page=10';
        $prev = 'http://localhost?page=2';
        $next = 'http://localhost?page=3';

        $mock = Mockery::mock(LengthAwarePaginator::class);
        $mock->shouldReceive('appends')->andReturnSelf();
        $mock->shouldReceive('lastPage')->once()->andReturn(10);
        $mock->shouldReceive('url')->andReturns($first, $last);
        $mock->shouldReceive('previousPageUrl')->once()->andReturn($prev);
        $mock->shouldReceive('nextPageUrl')->once()->andReturn($next);
        $paginator = new PaginatorLengthAwarePaginator($mock);

        $this->assertSame([
            'first' => $first,
            'last' => $last,
            'prev' => $prev,
            'next' => $next,
        ], $paginator->links());
    }
}
