<?php

namespace App\Providers\Post;

use App\Repositories\Post\EloquentPostRepository;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class PostRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, EloquentPostRepository::class);
    }
}
