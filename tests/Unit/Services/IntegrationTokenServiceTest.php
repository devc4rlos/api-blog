<?php

namespace Tests\Unit\Services;

use App\Dto\Persistence\AccessToken\CreateAccessTokenPersistenceDto;
use App\Models\User;
use App\Repositories\AccessToken\AccessTokenRepositoryInterface;
use App\Services\IntegrationTokenService;
use DomainException;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery;
use Tests\TestCase;

class IntegrationTokenServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(AccessTokenRepositoryInterface::class);
    }

    public function test_should_return_user_integration_token()
    {
        $user = Mockery::mock(User::class);
        $token = Mockery::mock(PersonalAccessToken::class);

        $this->repository->expects('getLastTokenByName')
            ->once()
            ->andReturn($token);

        $service = new IntegrationTokenService($this->repository);
        $service->getIntegrationToken($user);
    }

    public function test_should_create_integration_token_with_full_permission_and_no_expiration()
    {
        $user = Mockery::mock(User::class);
        $newAccessToken = Mockery::mock(NewAccessToken::class);

        $this->repository->expects('getLastTokenByName')
            ->once()
            ->andReturn(null);

        $this->repository->expects('createToken')
            ->once()
            ->with(
                $this->callback(function (CreateAccessTokenPersistenceDto $dto) use ($user) {
                    $this->assertSame($user, $dto->user());
                    $this->assertSame(['*'], $dto->abilities());
                    $this->assertSame(null, $dto->expiresAt());
                    return true;
                })
            )
            ->andReturn($newAccessToken);


        $service = new IntegrationTokenService($this->repository);
        $service->createIntegrationToken($user);
    }

    public function test_should_throw_domain_exception_when_creating_multiple_active_integration_tokens_per_user()
    {
        $user = Mockery::mock(User::class);
        $personalAccessToken = Mockery::mock(PersonalAccessToken::class);

        $this->repository->expects('getLastTokenByName')
            ->once()
            ->andReturn($personalAccessToken);

        $this->repository->shouldNotReceive('createToken');
        $this->expectException(DomainException::class);

        $service = new IntegrationTokenService($this->repository);
        $service->createIntegrationToken($user);
    }

    public function test_should_revoke_integration_token()
    {
        $user = Mockery::mock(User::class);
        $personalAccessToken = Mockery::mock(PersonalAccessToken::class);

        $this->repository->expects('getLastTokenByName')
            ->once()
            ->andReturn($personalAccessToken);

        $this->repository->expects('revokeToken')
            ->with($personalAccessToken)
            ->once();

        $service = new IntegrationTokenService($this->repository);
        $service->revokeIntegrationToken($user);
    }

    public function test_should_throw_domain_exception_when_revoking_nonexistent_user_integration_token()
    {
        $user = Mockery::mock(User::class);

        $this->repository->expects('getLastTokenByName')
            ->once()
            ->andReturn(null);

        $this->repository->shouldNotReceive('revokeToken');
        $this->expectException(DomainException::class);

        $service = new IntegrationTokenService($this->repository);
        $service->revokeIntegrationToken($user);
    }
}
