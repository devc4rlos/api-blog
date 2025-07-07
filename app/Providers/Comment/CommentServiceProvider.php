<?php

namespace App\Providers\Comment;

use App\Contracts\Services\CommentServiceInterface;
use App\Services\CommentService;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
    }
}
