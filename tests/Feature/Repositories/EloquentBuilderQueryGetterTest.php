<?php

namespace Tests\Feature\Repositories;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;
use App\Models\User;
use App\Repositories\EloquentBuilderQueryGetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Stubs\QueryPipelinesDTO\SortingPipelineStub;
use Tests\TestCase;

class EloquentBuilderQueryGetterTest extends TestCase
{
    use RefreshDatabase;

    private EloquentBuilderQueryGetter $queryGetter;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->count(20)->create();

        $query = User::query();
        $filtersDTO = new FiltersDto(sortBy: 'id', sortDirection:  'desc');
        $pipelinesDTO = new QueryPipelinesDto(SortingPipelineStub::class);

        $this->queryGetter = new EloquentBuilderQueryGetter($query, $filtersDTO, $pipelinesDTO);
    }

    public function test_should_apply_pipelines_and_return_paginated_results(): void
    {
        $count = 15;
        $result = $this->queryGetter->all();

        $this->assertCount($count, $result->items());
        $this->assertSame($result->pluck('id')->toArray(), User::orderBy('id', 'desc')->limit($count)->get()->pluck('id')->toArray());
    }

    public function test_should_apply_pipelines_and_find_a_specific_model(): void
    {
        $id = 5;
        $user = User::find($id);
        $userFindGetter = $this->queryGetter->find($user->id);

        $this->assertNotNull($userFindGetter);
        $this->assertSame($user->id, $userFindGetter->getAttribute('id'));
        $this->assertSame($user->id, $id);
    }
}
