<?php

namespace App\Dto\Persistence\QueryPipeline;

use App\Repositories\QueryPipelines\QueryPipelineInterface;

class QueryPipelinesDto
{
    private array $pipelines = [];

    public function __construct(string ...$pipelines)
    {
        $this->setPipelines($pipelines);
    }

    public function pipelines(): array
    {
        return $this->pipelines;
    }

    private function setPipelines(array $pipelines): void
    {
        foreach ($pipelines as $pipeline) {
            $this->setPipeline($pipeline);
        }
    }

    private function setPipeline(string $pipeline): void
    {
        if (!(app($pipeline) instanceof QueryPipelineInterface)) {
            return;
        }

        $this->pipelines[] = $pipeline;
    }
}
