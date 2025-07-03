<?php

namespace App\Repositories\QueryPipelines;

use App\Contracts\ModelCrudInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\QueryPipeline\PayloadQueryPipelineDto;
use Closure;

class OrderByQueryPipeline implements QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDto $payload, Closure $next)
    {
        $filter = $payload->filter();
        $query = $payload->query();
        $model = $payload->model();

        $sortBy = $this->getSortBy($filter, $model);
        $sortDirection = $this->getSortDirection($filter, $model);

        $query->orderBy($sortBy, $sortDirection);

        return $next($payload);
    }

    private function getSortBy(FiltersDto $filter, ModelCrudInterface $model): string
    {
        $allowedSortBy = $model->allowedSortBy();
        $defaultSortBy = $model->defaultSortBy();
        $sortBy = $filter->sortBy();

        return in_array($sortBy, $allowedSortBy) ? $sortBy : $defaultSortBy;
    }

    private function getSortDirection(FiltersDto $filter, ModelCrudInterface $model): string
    {
        $allowedSortDirection = $model->allowedSortDirection();
        $defaultSortDirection = $model->defaultSortDirection();
        $sortDirection = $filter->sortDirection();

        return in_array($sortDirection, $allowedSortDirection) ? $sortDirection : $defaultSortDirection;
    }
}

