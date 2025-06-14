<?php

namespace App\DTO\User;

use App\DTO\AbstractUpdatePersistenceDTO;

class UpdateUserPersistenceDTO extends AbstractUpdatePersistenceDTO
{
    protected function getAllowedKeys(): array
    {
        return ['name', 'email'];
    }

    protected function getModelName(): string
    {
        return 'User';
    }
}
