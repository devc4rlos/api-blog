<?php

namespace App\Repositories\AccessToken;

use App\Dto\AccessToken\CreateAccessTokenDto;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class EloquentAccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function createToken(CreateAccessTokenDto $accessTokenDTO): NewAccessToken
    {
        $user = $accessTokenDTO->user();
        return $user->createToken($accessTokenDTO->name(), $accessTokenDTO->abilities(), $accessTokenDTO->expiresAt());
    }

    public function revokeToken(PersonalAccessToken $token): bool
    {
        return $token->delete();
    }
}
