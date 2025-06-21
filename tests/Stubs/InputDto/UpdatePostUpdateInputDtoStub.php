<?php

namespace Tests\Stubs\InputDto;

use App\Contracts\Dto\InputDtoInterface;
use App\DTO\BaseUpdateInputDto;

class UpdatePostUpdateInputDtoStub extends BaseUpdateInputDto implements InputDtoInterface
{
    public function allowedFields(): array
    {
        return [
            'title',
            'price',
            'description',
        ];
    }
}
