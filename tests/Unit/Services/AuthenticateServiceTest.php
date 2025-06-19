<?php

namespace Tests\Unit\Services;

use App\DTO\Auth\AuthCredentialDTO;
use App\Models\User;
use App\Repositories\AccessToken\AccessTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AuthenticateService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthenticateServiceTest extends TestCase
{
    private MockInterface&UserRepositoryInterface $userRepository;
    private MockInterface&AccessTokenRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->repository = Mockery::mock(AccessTokenRepositoryInterface::class);
    }

    public static function provideCredentials(): array
    {
        return [
            ['password' => 'secret'],
            ['password' => 'password'],
            ['password' => fake()->password(8)],
        ];
    }

    /**
     * @throws AuthenticationException
     */
    #[DataProvider('provideCredentials')]
    public function test_should_authenticate(string $password): void
    {
        $textToken = fake()->uuid();

        $user = Mockery::mock(User::class)->makePartial();
        $user->password = bcrypt($password);

        $this->userRepository->shouldReceive('findByEmail')->once()->andReturn($user);

        $newAccessToken = Mockery::mock(NewAccessToken::class)->makePartial();
        $newAccessToken->plainTextToken = $textToken;

        $this->repository->shouldReceive('createToken')->once()->andReturn($newAccessToken);

        $service = new AuthenticateService($this->repository, $this->userRepository);
        $credentialDTO = new AuthCredentialDTO(fake()->email(), $password);

        $token = $service->authenticate($credentialDTO);

        $this->assertSame($textToken, $token);
    }

    #[DataProvider('provideCredentials')]
    public function test_should_throw_authentication_exception_when_trying_login_with_invalid_password(string $password): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage(__('services/authenticate.invalid_credentials'));

        $user = Mockery::mock(User::class)->makePartial();
        $this->userRepository->shouldReceive('findByEmail')->once()->andReturn($user);

        $service = new AuthenticateService($this->repository, $this->userRepository);
        $credentialDTO = new AuthCredentialDTO(fake()->email(), $password);

        $service->authenticate($credentialDTO);
    }

    public function test_should_revoke_token()
    {
        $id = 10;
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = $id;

        $this->repository->shouldReceive('revokeToken')->once()->andReturn(true);

        $service = new AuthenticateService($this->repository, $this->userRepository);
        $token = Mockery::mock(PersonalAccessToken::class)->makePartial();
        $token->tokenable_id = $id;

        $result = $service->logout($user, $token);

        $this->assertTrue($result);
    }

    public function test_should_throw_exception_if_it_tries_to_revoke_another_users_token()
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage(__('services/authenticate.invalid_token'));

        $idUser = 10;
        $idUserToken = 1;
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = $idUser;

        $service = new AuthenticateService($this->repository, $this->userRepository);
        $token = Mockery::mock(PersonalAccessToken::class)->makePartial();
        $token->tokenable_id = $idUserToken;

        $service->logout($user, $token);
    }
}
