<?php

namespace App\Dto\User;

class CreateUserPersistenceDto
{
    public string $name;
    public string $email;
    public string $password;
    public bool $isAdmin;

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
}
