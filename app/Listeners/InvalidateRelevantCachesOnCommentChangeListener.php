<?php

namespace App\Listeners;

use App\Events\CommentChangedEvent;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Psr\Log\LoggerInterface;

class InvalidateRelevantCachesOnCommentChangeListener
{
    private CacheFactory $cache;
    private LoggerInterface $logger;

    public function __construct(CacheFactory $cacheFactory, LoggerInterface $logger)
    {
        $this->cache = $cacheFactory;
        $this->logger = $logger;
    }

    public function handle(CommentChangedEvent $event): void
    {
        $this->cache->store()->tags('posts')->flush();
        $this->logger->info('Post cache flushed due to a comment change.', [
            'comment_id' => $event->comment->id,
            'post_id' => $event->comment->post_id,
            'tag' => 'posts'
        ]);

        $this->cache->store()->tags('comments')->flush();
        $this->logger->info('Comment cache flushed due to a comment change.', [
            'comment_id' => $event->comment->id,
            'tag' => 'comments'
        ]);
    }
}
