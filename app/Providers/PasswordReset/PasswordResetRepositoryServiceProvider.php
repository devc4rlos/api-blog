<?php

namespace App\Providers\PasswordReset;

use App\Repositories\PasswordReset\EloquentPasswordResetRepositoryRepository;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class PasswordResetRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PasswordResetRepositoryInterface::class, EloquentPasswordResetRepositoryRepository::class);
    }
}
