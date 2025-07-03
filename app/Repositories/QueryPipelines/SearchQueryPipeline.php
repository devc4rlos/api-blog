<?php

namespace App\Repositories\QueryPipelines;

use App\Dto\Persistence\QueryPipeline\PayloadQueryPipelineDto;
use Closure;

class SearchQueryPipeline implements QueryPipelineInterface
{
    public function handle(PayloadQueryPipelineDto $payload, Closure $next)
    {
        $filter = $payload->filter();
        $query = $payload->query();
        $model = $payload->model();
        $allowedFieldSearch = $model->allowedFieldSearch();

        $search = $filter->search();
        $searchBy = $filter->searchBy();

        if ($this->validateSearch($search, $searchBy, $allowedFieldSearch)) {
            $query->where($searchBy, 'like', '%' . $search . '%');
        }

        return $next($payload);
    }

    private function validateSearch(?string $search, ?string $searchBy, array $allowedFieldSearch): bool
    {
        return $search && in_array($searchBy, $allowedFieldSearch);
    }
}

