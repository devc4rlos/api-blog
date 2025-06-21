<?php

namespace App\Dto;

use App\Dto\Filter\FiltersDto;
use Illuminate\Database\Eloquent\Builder;

class PayloadQueryPipelineDto
{
    private Builder $query;
    private FiltersDto $filter;

    public function __construct(Builder $query, FiltersDto $filter)
    {
        $this->query = $query;
        $this->filter = $filter;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function filter(): FiltersDto
    {
        return $this->filter;
    }
}
