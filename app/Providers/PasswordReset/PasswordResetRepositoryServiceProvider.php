<?php

namespace App\Providers\PasswordReset;

use App\Repositories\PasswordReset\EloquentPasswordResetRepository;
use App\Repositories\PasswordReset\PasswordResetInterface;
use Illuminate\Support\ServiceProvider;

class PasswordResetRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PasswordResetInterface::class, EloquentPasswordResetRepository::class);
    }
}
