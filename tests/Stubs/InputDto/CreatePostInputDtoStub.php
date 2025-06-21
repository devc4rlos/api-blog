<?php

namespace Tests\Stubs\InputDto;

use App\Contracts\Dto\InputDtoInterface;

class CreatePostInputDtoStub implements InputDtoInterface
{
    private string $title;
    private float $price;
    private ?string $description;
    private bool $published;

    public function __construct(
        string $title,
        float $price,
        ?string $description = null,
        bool $published = false,
    )
    {
        $this->title = $title;
        $this->price = $price;
        $this->description = $description;
        $this->published = $published;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
            'published' => $this->published,
        ];
    }
}
