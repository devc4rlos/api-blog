<?php

namespace App\Contracts\Services;

use App\DTO\Auth\AuthCredentialDTO;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

interface AuthenticateServiceInterface
{
    public function authenticate(AuthCredentialDTO $credentialDTO): string;
    public function logout(User $user, PersonalAccessToken $token): bool;
}
