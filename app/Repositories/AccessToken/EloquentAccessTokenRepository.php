<?php

namespace App\Repositories\AccessToken;

use App\DTO\AccessToken\CreateAccessTokenDTO;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentAccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function createToken(CreateAccessTokenDTO $accessTokenDTO): NewAccessToken
    {
        $user = $accessTokenDTO->user();
        return $user->createToken($accessTokenDTO->name(), $accessTokenDTO->abilities(), $accessTokenDTO->expiresAt());
    }

    public function revokeToken(PersonalAccessToken $token): bool
    {
        return $token->delete();
    }
}
