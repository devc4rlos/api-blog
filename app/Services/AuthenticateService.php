<?php

namespace App\Services;

use App\Contracts\Services\AuthenticateServiceInterface;
use App\Dto\Input\Auth\AuthCredentialInputDto;
use App\Dto\Persistence\AccessToken\CreateAccessTokenPersistenceDto;
use App\Models\User;
use App\Repositories\AccessToken\AccessTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateService implements AuthenticateServiceInterface
{
    private AccessTokenRepositoryInterface $repository;
    private UserRepositoryInterface $userRepository;

    public function __construct(AccessTokenRepositoryInterface $repository, UserRepositoryInterface $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws AuthenticationException
     */
    public function authenticate(AuthCredentialInputDto $credentialDTO): string
    {
        $user = $this->userRepository->findByEmail($credentialDTO->email());

        if (!$user || !$this->checkPassword($user, $credentialDTO->password())) {
            throw new AuthenticationException(__('services/authenticate.invalid_credentials'));
        }

        $dto = new CreateAccessTokenPersistenceDto($user, 'api', [], now()->addMinutes(config('sanctum.expiration')));
        $newAccessToken = $this->repository->createToken($dto);

        return $newAccessToken->plainTextToken;
    }

    /**
     * @throws UnauthorizedException
     */
    public function logout(User $user, PersonalAccessToken $token): bool
    {
        if ($user->id !== $token->tokenable_id) {
            throw new UnauthorizedException(__('services/authenticate.invalid_token'));
        }
        return $this->repository->revokeToken($token);
    }

    private function checkPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }
}
