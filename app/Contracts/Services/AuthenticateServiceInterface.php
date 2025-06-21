<?php

namespace App\Contracts\Services;

use App\Dto\Input\Auth\AuthCredentialInputDto;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

interface AuthenticateServiceInterface
{
    public function authenticate(AuthCredentialInputDto $credentialDTO): string;
    public function logout(User $user, PersonalAccessToken $token): bool;
}
