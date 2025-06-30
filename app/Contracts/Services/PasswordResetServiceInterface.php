<?php

namespace App\Contracts\Services;

use App\Dto\Input\PasswordReset\PasswordResetInputDto;

interface PasswordResetServiceInterface
{
    public function forgotPassword(string $email);

    public function resetPassword(PasswordResetInputDto $passwordResetDto);
}
