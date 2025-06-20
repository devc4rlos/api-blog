<?php

namespace App\DTO\User;

use App\DTO\AbstractUpdatePersistenceDTO;

class UpdateUserPersistenceDTO extends AbstractUpdatePersistenceDTO
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
