<?php

namespace App\Dto;

abstract class BaseUpdatePersistenceDto
{
    private array $changes;

    public function __construct(array $changes)
    {
        $this->changes = $changes;
    }

    public function toArray(): array
    {
        return $this->changes;
    }
}
