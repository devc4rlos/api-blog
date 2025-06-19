<?php

namespace App\Repositories\AccessToken;

use App\DTO\AccessToken\CreateAccessTokenDTO;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

interface AccessTokenRepositoryInterface
{
    public function createToken(CreateAccessTokenDTO $accessTokenDTO): NewAccessToken;
    public function revokeToken(PersonalAccessToken $token): bool;
}
