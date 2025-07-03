<?php

namespace App\Repositories;

use App\Contracts\ModelCrudInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\QueryPipeline\PayloadQueryPipelineDto;
use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;
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
    private ModelCrudInterface $model;

    public function __construct(Builder $query, FiltersDto $filtersDTO, QueryPipelinesDto $pipelinesDTO, ModelCrudInterface $model)
    {
        $this->query = $query;
        $this->filtersDTO = $filtersDTO;
        $this->pipelinesDTO = $pipelinesDTO;
        $this->model = $model;
    }

    public function all(): LengthAwarePaginator
    {
        $payload = new PayloadQueryPipelineDto($this->query, $this->filtersDTO, $this->model);

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
        $payload = new PayloadQueryPipelineDto($this->query, $this->filtersDTO, $this->model);

        return app(Pipeline::class)
            ->send($payload)
            ->through($this->pipelinesDTO->pipelines())
            ->then(fn($payload) => $payload->query()->find($id));
    }
}
