<?php

namespace App\Dto\Input;

abstract class BaseUpdateInputDto
{
    protected array $changes;

    public function __construct(array $changes)
    {
        $this->changes = $changes;
    }

    public function toArray(): array
    {
        $allowedFields = $this->allowedFields();
        return array_filter($this->changes, function ($field) use ($allowedFields) {
            return in_array($field, $allowedFields);
        }, ARRAY_FILTER_USE_KEY);
    }

    abstract public function allowedFields(): array;
}
