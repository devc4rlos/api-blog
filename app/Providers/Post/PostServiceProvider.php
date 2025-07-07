<?php

namespace App\Providers\Post;

use App\Contracts\Services\PostServiceInterface;
use App\Decorators\Post\PostLogServiceDecorator;
use App\Repositories\Post\PostRepositoryInterface;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class PostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PostServiceInterface::class, function ($app) {
            $repository = $app->make(PostRepositoryInterface::class);
            $logger = $app->make(LoggerInterface::class);
            $baseService = new PostService($repository);

            return new PostLogServiceDecorator($baseService, $logger);
        });
    }
}
