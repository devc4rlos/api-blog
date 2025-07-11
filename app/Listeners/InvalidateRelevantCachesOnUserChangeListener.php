<?php

namespace App\Listeners;

use App\Events\UserChangedEvent;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Psr\Log\LoggerInterface;

class InvalidateRelevantCachesOnUserChangeListener
{
    private CacheFactory $cache;
    private LoggerInterface $logger;

    public function __construct(CacheFactory $cacheFactory, LoggerInterface $logger)
    {
        $this->cache = $cacheFactory;
        $this->logger = $logger;
    }

    public function handle(UserChangedEvent $event): void
    {
        $this->cache->store()->tags('users')->flush();
        $this->logger->info('User cache flushed due to a user change.', [
            'user_id' => $event->user->id,
            'user_email' => $event->user->email,
            'tag' => 'users'
        ]);
    }
}
