<?php

namespace App\Dto\Input\PasswordReset;

class PasswordResetInputDto
{
    private string $email;
    private string $code;
    private string $password;

    public function __construct(string $email, string $code, string $password)
    {
        $this->email = $email;
        $this->code = $code;
        $this->password = $password;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function password(): string
    {
        return $this->password;
    }
}
