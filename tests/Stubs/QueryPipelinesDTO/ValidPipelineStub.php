<?php

namespace Tests\Stubs\QueryPipelinesDTO;

use App\Dto\PayloadQueryPipelineDto;
use App\Repositories\QueryPipelines\QueryPipelineInterface;
use Closure;

class ValidPipelineStub implements QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDto $payload, Closure $next)
    {
    }
}
