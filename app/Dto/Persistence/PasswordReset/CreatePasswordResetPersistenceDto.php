<?php

namespace App\Dto\Persistence\PasswordReset;

class CreatePasswordResetPersistenceDto
{
    private string $email;
    private string $code;

    public function __construct(string $email, string $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function code(): string
    {
        return $this->code;
    }
}
