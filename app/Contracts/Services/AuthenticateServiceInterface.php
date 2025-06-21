<?php

namespace App\Contracts\Services;

use App\Dto\Auth\AuthCredentialDto;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

interface AuthenticateServiceInterface
{
    public function authenticate(AuthCredentialDto $credentialDTO): string;
    public function logout(User $user, PersonalAccessToken $token): bool;
}
