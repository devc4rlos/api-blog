<?php

namespace App\Decorators\Auth;

use App\Contracts\Services\AuthenticateServiceInterface;
use App\Dto\Auth\AuthCredentialDto;
use App\Models\User;
use App\Services\AuthenticateService;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\PersonalAccessToken;
use Psr\Log\LoggerInterface;

class AuthenticateLogServiceDecorator implements AuthenticateServiceInterface
{
    private AuthenticateService $service;
    private LoggerInterface $logger;

    public function __construct(AuthenticateService $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    /**
     * @throws AuthenticationException
     */
    public function authenticate(AuthCredentialDto $credentialDTO): string
    {
        $context = ['email' => $credentialDTO->email()];
        $this->logger->info('Authentication attempt started.', $context);

        try {
            $success = $this->service->authenticate($credentialDTO);

            $this->logger->info('Authentication attempt finished.', $context);
        } catch (Exception $e) {
            $this->logger->warning('Authentication failed.', $context);

            throw $e;
        }

        return $success;
    }

    /**
     * @throws Exception
     */
    public function logout(User $user, PersonalAccessToken $token): bool
    {
        $this->logger->info('Logout attempt started.', ['user_id' => $user->id, 'token_id' => $token->id]);

        try {
            $success = $this->service->logout($user, $token);

            if ($success) {
                $this->logger->info('Logout successful. Token has been revoked.', ['user_id' => $user->id, 'token_id' => $token->id]);
            }
        } catch (Exception $e) {
            $this->logger->error('Unauthorized logout attempt: Token does not belong to the user.', ['user_id' => $user->id, 'token_id' => $token->id, 'token_owner_id' => $token->tokenable_id]);
            throw $e;
        }

        return $success;
    }
}
