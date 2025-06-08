<?php

namespace App\Repositories\QueryPipelines;

use App\DTO\PayloadQueryPipelineDTO;
use Closure;

interface QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDTO $payload, Closure $next);
}
