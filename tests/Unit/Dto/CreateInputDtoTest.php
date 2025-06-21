<?php

namespace Tests\Unit\Dto;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Stubs\InputDto\CreatePostInputDtoStub;
use Tests\TestCase;

class CreateInputDtoTest extends TestCase
{
    public static function providePostData(): array
    {
        $title = fake()->text(80);
        $price = fake()->randomFloat(2, 1, 100);
        $description = fake()->text();
        $published = fake()->boolean();
        return [
            [
                'title' => $title,
                'price' => $price,
                'description' => $description,
                'published' => $published,
            ],
            [
                'title' => fake()->text(80),
                'price' => $price,
                'description' => null,
                'published' => $published,
            ],
        ];
    }

    #[DataProvider('providePostData')]
    public function test_create_dto_should_be_correctly_converted_to_array(
        string $title,
        float $price,
        ?string $description,
        bool $published,
    )
    {
        $dto = new CreatePostInputDtoStub(
            title: $title,
            price: $price,
            description: $description,
            published: $published,
        );

        $expectedArray = [
            'title' => $title,
            'price' => $price,
            'description' => $description,
            'published' => $published,
        ];

        $this->assertEquals($expectedArray, $dto->toArray());
    }
}
