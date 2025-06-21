<?php

namespace App\Dto\User;

use App\Dto\AbstractUpdatePersistenceDto;

class UpdateUserPersistenceDto extends AbstractUpdatePersistenceDto
{
    protected function getAllowedKeys(): array
    {
        return ['name', 'email', 'is_admin'];
    }

    protected function getModelName(): string
    {
        return 'User';
    }
}
