<?php

namespace App\Listeners;

use App\Events\PostChangedEvent;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Psr\Log\LoggerInterface;

class InvalidateRelevantCachesOnPostChangeListener
{
    private CacheFactory $cache;
    private LoggerInterface $logger;

    public function __construct(CacheFactory $cacheFactory, LoggerInterface $logger)
    {
        $this->cache = $cacheFactory;
        $this->logger = $logger;
    }

    public function handle(PostChangedEvent $event): void
    {
        $this->cache->store()->tags('posts')->flush();
        $this->logger->info('Post cache flushed due to a post change.', [
            'post_id' => $event->post->id,
            'post_title' => $event->post->title,
            'tag' => 'posts'
        ]);
    }
}
