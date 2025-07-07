<?php

namespace App\Providers\Post;

use App\Decorators\Post\PostCacheRepositoryDecorator;
use App\Repositories\Post\EloquentPostRepository;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class PostRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, function ($app) {
            $baseRepository = new EloquentPostRepository();

            return new PostCacheRepositoryDecorator(
                $baseRepository,
                $app->make('cache'),
                $app->make(LoggerInterface::class),
            );
        });
    }
}
