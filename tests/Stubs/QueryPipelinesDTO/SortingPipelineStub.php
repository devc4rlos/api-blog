<?php

namespace Tests\Stubs\QueryPipelinesDTO;

use App\Dto\Persistence\QueryPipeline\PayloadQueryPipelineDto;
use App\Repositories\QueryPipelines\QueryPipelineInterface;
use Closure;

class SortingPipelineStub implements QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDto $payload, Closure $next)
    {
        $query = $payload->query();
        $filter = $payload->filter();

        $query->orderBy($filter->sortBy(), $filter->sortDirection());

        return $next($payload);
    }
}
