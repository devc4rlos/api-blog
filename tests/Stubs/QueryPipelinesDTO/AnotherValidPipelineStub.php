<?php

namespace Tests\Stubs\QueryPipelinesDTO;

use App\Dto\Persistence\QueryPipeline\PayloadQueryPipelineDto;
use App\Repositories\QueryPipelines\QueryPipelineInterface;
use Closure;

class AnotherValidPipelineStub implements QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDto $payload, Closure $next)
    {
    }
}
