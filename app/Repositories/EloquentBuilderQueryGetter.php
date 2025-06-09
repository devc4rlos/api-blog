<?php

namespace App\Repositories;

use App\DTO\Filter\FiltersDTO;
use App\DTO\PayloadQueryPipelineDTO;
use App\DTO\QueryPipelinesDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class EloquentBuilderQueryGetter
{
    private Builder $query;
    private FiltersDTO $filtersDTO;
    private QueryPipelinesDTO $pipelinesDTO;

    public function __construct(Builder $query, FiltersDTO $filtersDTO, QueryPipelinesDTO $pipelinesDTO)
    {
        $this->query = $query;
        $this->filtersDTO = $filtersDTO;
        $this->pipelinesDTO = $pipelinesDTO;
    }

    public function all(): LengthAwarePaginator
    {
        $payload = new PayloadQueryPipelineDTO($this->query, $this->filtersDTO);

        return app(Pipeline::class)
            ->send($payload)
            ->through($this->pipelinesDTO->pipelines())
            ->then(fn(PayloadQueryPipelineDTO $payload) => $payload->query()->paginate());
    }

    public function find($id): ?Model
    {
        $payload = new PayloadQueryPipelineDTO($this->query, $this->filtersDTO);

        return app(Pipeline::class)
            ->send($payload)
            ->through($this->pipelinesDTO->pipelines())
            ->then(fn($payload) => $payload->query()->find($id));
    }
}
