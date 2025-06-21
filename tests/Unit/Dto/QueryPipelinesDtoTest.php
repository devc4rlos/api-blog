<?php

namespace Tests\Unit\Dto;

use App\Dto\QueryPipelinesDto;
use Tests\Stubs\QueryPipelinesDTO\AnotherValidPipelineStub;
use Tests\Stubs\QueryPipelinesDTO\InvalidPipelineStub;
use Tests\Stubs\QueryPipelinesDTO\ValidPipelineStub;
use Tests\TestCase;

class QueryPipelinesDtoTest extends TestCase
{
    public function test_should_store_pipelines_that_implement_the_interface()
    {
        $pipelines = [
            ValidPipelineStub::class,
            AnotherValidPipelineStub::class,
        ];

        $dto = new QueryPipelinesDto(...$pipelines);

        $this->assertCount(2, $dto->pipelines());
        $this->assertEquals($pipelines, $dto->pipelines());
    }

    public function test_should_filter_out_pipelines_that_do_not_implement_the_interface()
    {
        $mixedPipelines = [
            ValidPipelineStub::class,
            InvalidPipelineStub::class,
            AnotherValidPipelineStub::class,
        ];

        $dto = new QueryPipelinesDto(...$mixedPipelines);

        $expectedPipelines = [
            ValidPipelineStub::class,
            AnotherValidPipelineStub::class,
        ];

        $this->assertCount(2, $dto->pipelines());
        $this->assertEquals($expectedPipelines, $dto->pipelines());
    }

    public function test_should_return_an_empty_array_if_only_invalid_pipelines_are_provided()
    {
        $invalidPipelines = [
            InvalidPipelineStub::class,
        ];

        $dto = new QueryPipelinesDto(...$invalidPipelines);

        $this->assertEmpty($dto->pipelines());
        $this->assertCount(0, $dto->pipelines());
    }

    public function test_should_be_empty_when_instantiated_with_no_pipelines(): void
    {
        $dto = new QueryPipelinesDto();

        $this->assertEmpty($dto->pipelines());
    }
}
