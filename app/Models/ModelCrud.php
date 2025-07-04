<?php

namespace App\Models;

use App\Contracts\ModelCrudInterface;
use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;
use App\Repositories\QueryPipelines\OrderByQueryPipeline;
use App\Repositories\QueryPipelines\SearchQueryPipeline;
use Illuminate\Database\Eloquent\Model;

abstract class ModelCrud extends Model implements ModelCrudInterface
{
    public static function pipelinesFindAll(): QueryPipelinesDto
    {
        return new QueryPipelinesDto(OrderByQueryPipeline::class, SearchQueryPipeline::class);
    }

    public static function pipelinesFindOne(): QueryPipelinesDto
    {
        return new QueryPipelinesDto(OrderByQueryPipeline::class);
    }

    public function allowedSortBy(): array
    {
        return ['created_at'];
    }

    public function defaultSortBy(): string
    {
        return 'created_at';
    }

    public function allowedSortDirection(): array
    {
        return ['asc', 'desc'];
    }

    public function defaultSortDirection(): string
    {
        return 'desc';
    }
}
