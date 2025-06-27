<?php

namespace App\Dto\Input\User;

class CreateUserInputDto
{
    private string $name;
    private string $email;
    private string $password;
    private bool $isAdmin;

    public function __construct(
        string $name,
        string $email,
        string $password,
        bool $isAdmin = false,
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
}
