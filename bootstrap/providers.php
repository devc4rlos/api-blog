<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ResponseApiServiceProvider::class,
    App\Providers\User\UserRepositoryServiceProvider::class,
    App\Providers\User\UserServiceProvider::class,
    App\Providers\Auth\AuthenticateServiceProvider::class,
    App\Providers\Auth\AccessTokenRepositoryServiceProvider::class,
    App\Providers\PasswordReset\PasswordResetRepositoryServiceProvider::class,
    App\Providers\RateLimitingApiServiceProvider::class,
    App\Providers\RateLimitingAuthServiceProvider::class,
    App\Providers\Post\PostRepositoryServiceProvider::class,
    App\Providers\Post\PostServiceProvider::class,
    App\Providers\Comment\CommentRepositoryServiceProvider::class,
];
