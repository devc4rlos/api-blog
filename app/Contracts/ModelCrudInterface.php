<?php

namespace App\Contracts;

use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;

interface ModelCrudInterface
{
    public static function pipelinesFindAll(): QueryPipelinesDto;
    public static function pipelinesFindOne(): QueryPipelinesDto;
}
