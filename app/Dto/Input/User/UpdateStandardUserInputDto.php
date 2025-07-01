<?php

namespace App\Dto\Input\User;

class UpdateStandardUserInputDto extends UpdateUserInputDto
{
    public function allowedFields(): array
    {
        return ['name', 'email'];
    }
}
