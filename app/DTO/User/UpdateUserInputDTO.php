<?php

namespace App\DTO\User;

class UpdateUserInputDTO
{
    private array $data;

    private array $providedKeys;

    public function __construct(array $requestData)
    {
        $this->data = $requestData;
        $this->providedKeys = array_keys($requestData);
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->providedKeys, true);
    }

    public function name(): ?string
    {
        return $this->data['name'] ?? null;
    }

    public function email(): ?string
    {
        return $this->data['email'] ?? null;
    }
}
