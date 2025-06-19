<?php

namespace App\Providers\Auth;

use App\Repositories\AccessToken\AccessTokenRepositoryInterface;
use App\Repositories\AccessToken\EloquentAccessTokenRepository;
use Illuminate\Support\ServiceProvider;

class AccessTokenRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AccessTokenRepositoryInterface::class, EloquentAccessTokenRepository::class);
    }
}
