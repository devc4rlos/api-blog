<?php

namespace App\Dto\Persistence\QueryPipeline;

use App\Contracts\ModelCrudInterface;
use App\Dto\Filter\FiltersDto;
use Illuminate\Database\Eloquent\Builder;

class PayloadQueryPipelineDto
{
    private Builder $query;
    private FiltersDto $filter;
    private ModelCrudInterface $model;

    public function __construct(Builder $query, FiltersDto $filter, ModelCrudInterface $model)
    {
        $this->query = $query;
        $this->filter = $filter;
        $this->model = $model;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function filter(): FiltersDto
    {
        return $this->filter;
    }

    public function model(): ModelCrudInterface
    {
        return $this->model;
    }
}
