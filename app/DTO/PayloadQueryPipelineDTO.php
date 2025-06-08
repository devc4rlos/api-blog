<?php

namespace App\DTO;

use App\DTO\Filter\FiltersDTO;
use Illuminate\Database\Eloquent\Builder;

readonly class PayloadQueryPipelineDTO
{
    private Builder $query;
    private FiltersDTO $filter;

    public function __construct(Builder $query, FiltersDTO $filter)
    {
        $this->query = $query;
        $this->filter = $filter;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function filter(): FiltersDTO
    {
        return $this->filter;
    }
}
