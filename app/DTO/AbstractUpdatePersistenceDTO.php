<?php

namespace App\DTO;

use InvalidArgumentException;

abstract class AbstractUpdatePersistenceDTO
{
    public array $changes;

    public function __construct(array $changes)
    {
        $this->validateChanges($changes);
        $this->changes = $changes;
    }

    abstract protected function getAllowedKeys(): array;

    abstract protected function getModelName(): string;

    private function validateChanges(array $changes): void
    {
        $allowedKeys = $this->getAllowedKeys();
        $modelName = $this->getModelName();

        foreach (array_keys($changes) as $key) {
            if (!in_array($key, $allowedKeys, true)) {
                throw new InvalidArgumentException(
                    "The key '$key' is not an updatable field for $modelName."
                );
            }
        }
    }

    public function toArray(): array
    {
        return $this->changes;
    }
}
