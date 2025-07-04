<?php

namespace App\Providers\Post;

use App\Contracts\Services\PostServiceInterface;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PostServiceInterface::class, PostService::class);
    }
}
