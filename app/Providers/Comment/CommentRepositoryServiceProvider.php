<?php

namespace App\Providers\Comment;

use App\Decorators\Comment\CommentCacheRepositoryDecorator;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Comment\EloquentCommentRepository;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class CommentRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CommentRepositoryInterface::class, function ($app) {
            $baseRepository = new EloquentCommentRepository();

            return new CommentCacheRepositoryDecorator(
                $baseRepository,
                $app->make('cache'),
                $app->make(LoggerInterface::class)
            );
        });
    }
}
