<?php

namespace App\DTO\AccessToken;

use App\Models\User;
use DateTimeInterface;

class CreateAccessTokenDTO
{
    private User $user;
    private string $name;
    private array $abilities;
    private DateTimeInterface $expiresAt;

    public function __construct(
        User $user,
        string $name,
        array $abilities,
        DateTimeInterface $expiresAt,
    )
    {
        $this->user = $user;
        $this->name = $name;
        $this->abilities = $abilities;
        $this->expiresAt = $expiresAt;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function abilities(): array
    {
        return $this->abilities;
    }

    public function expiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}
