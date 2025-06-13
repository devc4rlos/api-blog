<?php

namespace App\Providers\User;

use App\Contracts\Services\UserServiceInterface;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }
}
