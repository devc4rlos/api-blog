<?php

namespace App\Repositories\AccessToken;

use App\Dto\Persistence\AccessToken\CreateAccessTokenPersistenceDto;
use App\Models\User;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

interface AccessTokenRepositoryInterface
{
    public function getLastTokenByName(User $user, string $name): ?PersonalAccessToken;
    public function createToken(CreateAccessTokenPersistenceDto $accessTokenDTO): NewAccessToken;
    public function revokeToken(PersonalAccessToken $token): bool;
}
