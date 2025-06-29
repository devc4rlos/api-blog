<?php

namespace App\Repositories\PasswordReset;

use App\Dto\Persistence\PasswordReset\CreatePasswordResetPersistenceDto;
use App\Models\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function findLastCodeByEmail(string $email): PasswordReset;
    public function create(CreatePasswordResetPersistenceDto $passwordResetDto): PasswordReset;
    public function delete(string $code): bool;
}
