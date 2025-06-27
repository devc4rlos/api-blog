<?php

namespace App\Dto\Input\User;

use App\Dto\Input\BaseUpdateInputDto;

class UpdateUserInputDto extends BaseUpdateInputDto
{
    public function allowedFields(): array
    {
        return ['name', 'email', 'is_admin'];
    }
}
