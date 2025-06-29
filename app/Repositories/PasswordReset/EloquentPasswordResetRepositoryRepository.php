<?php

namespace App\Repositories\PasswordReset;

use App\Dto\Persistence\PasswordReset\CreatePasswordResetPersistenceDto;
use App\Models\PasswordReset;

class EloquentPasswordResetRepositoryRepository implements PasswordResetRepositoryInterface
{
    public function findLastCodeByEmail(string $email): ?PasswordReset
    {
        return PasswordReset::where('email', $email)->get()->last();
    }

    public function create(CreatePasswordResetPersistenceDto $passwordResetDto): PasswordReset
    {
        return PasswordReset::create([
            'email' => $passwordResetDto->email(),
            'token' => $passwordResetDto->code(),
        ]);
    }

    public function delete(string $code): bool
    {
        return PasswordReset::where('token', $code)->delete();
    }
}
