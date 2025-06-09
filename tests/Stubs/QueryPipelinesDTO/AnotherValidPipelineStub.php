<?php

namespace Tests\Stubs\QueryPipelinesDTO;

use App\DTO\PayloadQueryPipelineDTO;
use App\Repositories\QueryPipelines\QueryPipelineInterface;
use Closure;

class AnotherValidPipelineStub implements QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDTO $payload, Closure $next)
    {
    }
}
