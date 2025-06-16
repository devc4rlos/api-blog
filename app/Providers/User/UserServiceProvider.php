<?php

namespace App\Providers\User;

use App\Contracts\Services\UserServiceInterface;
use App\Decorators\User\UserLogServiceDecorator;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, function ($app) {
            $logger = $app->make(LoggerInterface::class);
            $repository = $app->make(UserRepositoryInterface::class);
            $baseService = new UserService($repository);

            return new UserLogServiceDecorator($baseService, $logger);
        });
    }
}
