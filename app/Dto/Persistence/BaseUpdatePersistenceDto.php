<?php

namespace App\Dto\Persistence;

abstract class BaseUpdatePersistenceDto
{
    protected array $changes;

    public function __construct(array $changes)
    {
        $this->changes = $changes;
    }

    public function toArray(): array
    {
        return $this->changes;
    }
}
