<?php

namespace App\Providers\Auth;

use App\Contracts\Services\AuthenticateServiceInterface;
use App\Decorators\Auth\AuthenticateLogServiceDecorator;
use App\Repositories\AccessToken\AccessTokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AuthenticateService;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AuthenticateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthenticateServiceInterface::class, function ($app) {
            $userRepository = $app->make(UserRepositoryInterface::class);
            $accessTokenRepository = $app->make(AccessTokenRepositoryInterface::class);
            $baseService = new AuthenticateService($accessTokenRepository, $userRepository);

            return new AuthenticateLogServiceDecorator(
                $baseService,
                $app->make(LoggerInterface::class),
            );
        });
    }
}
