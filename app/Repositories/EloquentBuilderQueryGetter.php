<?php

namespace App\Repositories;

use App\Dto\Filter\FiltersDto;
use App\Dto\PayloadQueryPipelineDto;
use App\Dto\QueryPipelinesDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

/**
 * @template TModel of Model
 */
class EloquentBuilderQueryGetter
{
    private Builder $query;
    private FiltersDto $filtersDTO;
    private QueryPipelinesDto $pipelinesDTO;

    public function __construct(Builder $query, FiltersDto $filtersDTO, QueryPipelinesDto $pipelinesDTO)
    {
        $this->query = $query;
        $this->filtersDTO = $filtersDTO;
        $this->pipelinesDTO = $pipelinesDTO;
    }

    public function all(): LengthAwarePaginator
    {
        $payload = new PayloadQueryPipelineDto($this->query, $this->filtersDTO);

        return app(Pipeline::class)
            ->send($payload)
            ->through($this->pipelinesDTO->pipelines())
            ->then(fn(PayloadQueryPipelineDto $payload) => $payload->query()->paginate());
    }

    /**
     * @return TModel
     */
    public function find($id)
    {
        $payload = new PayloadQueryPipelineDto($this->query, $this->filtersDTO);

        return app(Pipeline::class)
            ->send($payload)
            ->through($this->pipelinesDTO->pipelines())
            ->then(fn($payload) => $payload->query()->find($id));
    }
}
