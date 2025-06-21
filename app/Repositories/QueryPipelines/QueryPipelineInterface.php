<?php

namespace App\Repositories\QueryPipelines;

use App\Dto\PayloadQueryPipelineDto;
use Closure;

interface QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDto $payload, Closure $next);
}
