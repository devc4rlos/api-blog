<?php

namespace App\Services;

use App\Contracts\Services\IntegrationTokenServiceInterface;
use App\Dto\Persistence\AccessToken\CreateAccessTokenPersistenceDto;
use App\Models\User;
use App\Repositories\AccessToken\AccessTokenRepositoryInterface;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

class IntegrationTokenService implements IntegrationTokenServiceInterface
{
    private AccessTokenRepositoryInterface $repository;
    private readonly string $integrationKey;

    public function __construct(AccessTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->integrationKey = 'integration-token';
    }

    public function getIntegrationToken(User $user): ?PersonalAccessToken
    {
        return $this->repository->getLastTokenByName($user, $this->integrationKey);
    }

    public function createIntegrationToken(User $user): NewAccessToken
    {
        $integrationToken = $this->repository->getLastTokenByName($user, $this->integrationKey);

        if ($integrationToken) {
            throw new \DomainException('This admin already has an active Integration Token.');
        }

        $dto = new CreateAccessTokenPersistenceDto($user, $this->integrationKey, ['*'], null);
        return $this->repository->createToken($dto);
    }

    public function revokeIntegrationToken(User $user): bool
    {
        $integrationToken = $this->repository->getLastTokenByName($user, $this->integrationKey);

        if (!$integrationToken) {
            throw new \DomainException('This admin does not have an active Integration Token.');
        }

        return $this->repository->revokeToken($integrationToken);
    }
}
