<?php

namespace App\Contracts;

use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;

interface ModelCrudInterface
{
    public static function pipelinesFindAll(): QueryPipelinesDto;
    public static function pipelinesFindOne(): QueryPipelinesDto;
    public function allowedSortBy(): array;
    public function defaultSortBy(): string;
    public function allowedSortDirection(): array;
    public function defaultSortDirection(): string;
}
