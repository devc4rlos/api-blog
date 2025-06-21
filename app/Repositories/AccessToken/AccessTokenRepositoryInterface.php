<?php

namespace App\Repositories\AccessToken;

use App\Dto\AccessToken\CreateAccessTokenDto;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

interface AccessTokenRepositoryInterface
{
    public function createToken(CreateAccessTokenDto $accessTokenDTO): NewAccessToken;
    public function revokeToken(PersonalAccessToken $token): bool;
}
