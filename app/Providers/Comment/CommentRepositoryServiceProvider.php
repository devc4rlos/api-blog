<?php

namespace App\Providers\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Comment\EloquentCommentRepository;
use Illuminate\Support\ServiceProvider;

class CommentRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CommentRepositoryInterface::class, EloquentCommentRepository::class);
    }
}
