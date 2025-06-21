<?php

namespace Tests\Unit\Dto;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Stubs\InputDto\UpdatePostUpdateInputDtoStub;
use Tests\TestCase;

class UpdateInputDtoTest extends TestCase
{
    public static function providerPostData(): array
    {
        return [
            'all defined fields' => [
                'input'          => ['title' => 'Título Completo', 'price' => 123.45],
                'expectedOutput' => ['title' => 'Título Completo', 'price' => 123.45],
            ],
            'only one defined field (ignores undefined fields)' => [
                'input'          => ['price' => 123.45],
                'expectedOutput' => ['price' => 123.45],
            ],
            'explicitly null field should be included' => [
                'input'          => ['description' => null],
                'expectedOutput' => ['description' => null],
            ],
            'empty string field should be included' => [
                'input'          => ['title' => ''],
                'expectedOutput' => ['title' => ''],
            ],
            'zero value field should be included' => [
                'input'          => ['price' => 0],
                'expectedOutput' => ['price' => 0],
            ],
        ];
    }

    #[DataProvider('providerPostData')]
    public function test_handles_various_valid_data_scenarios(array $input, array $expectedOutput): void
    {
        $dto = new UpdatePostUpdateInputDtoStub($input);

        $result = $dto->toArray();

        $this->assertEquals($expectedOutput, $result);
    }

    public function test_should_filter_out_disallowed_fields(): void
    {
        $title = fake()->realText(40);
        $input = [
            'title' => $title,
            'published' => true,
        ];
        $dto = new UpdatePostUpdateInputDtoStub($input);

        $result = $dto->toArray();

        $expected = ['title' => $title];
        $this->assertEquals($expected, $result);
    }
}
