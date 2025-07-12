<?php

namespace App\Repositories\AccessToken;

use App\Dto\Persistence\AccessToken\CreateAccessTokenPersistenceDto;
use App\Models\User;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentAccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function getLastTokenByName(User $user, string $name): ?PersonalAccessToken
    {
        return $user->tokens()->where('name', $name)->get()->last();
    }

    public function createToken(CreateAccessTokenPersistenceDto $accessTokenDTO): NewAccessToken
    {
        $user = $accessTokenDTO->user();
        return $user->createToken($accessTokenDTO->name(), $accessTokenDTO->abilities(), $accessTokenDTO->expiresAt());
    }

    public function revokeToken(PersonalAccessToken $token): bool
    {
        return $token->delete();
    }
}
