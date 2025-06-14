<?php

namespace App\Providers\User;

use App\Decorators\User\UserCacheRepositoryDecorator;
use App\Repositories\User\EloquentUserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            $baseRepository = new EloquentUserRepository();

            return new UserCacheRepositoryDecorator(
                $baseRepository,
                $app->make('cache')
            );
        });
    }
}
