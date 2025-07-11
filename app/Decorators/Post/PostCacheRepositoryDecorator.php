<?php

namespace App\Decorators\Post;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Helpers\CreateCacheKeyHelper;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
use Exception;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Log\LoggerInterface;

class PostCacheRepositoryDecorator implements PostRepositoryInterface
{
    private PostRepositoryInterface $repository;
    private CacheRepository $cache;
    private int $ttl = 15;
    private string $cacheTag = 'posts';
    private LoggerInterface $logger;

    public function __construct(PostRepositoryInterface $repository, CacheFactory $cacheFactory, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->cache = $cacheFactory->store()->tags($this->cacheTag);
        $this->logger = $logger;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forIndex('all', 'posts', $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey) {
            $this->logger->debug('Cache MISS for all posts. Fetching from repository.', [
                'key' => $cacheKey,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->all($filtersDTO);
        });
    }

    public function allPublished(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forIndex('allPublished', 'posts', $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey) {
            $this->logger->debug('Cache MISS for all posts published. Fetching from repository.', [
                'key' => $cacheKey,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->allPublished($filtersDTO);
        });
    }

    public function findById(string $id, FiltersDto $filtersDTO): Post
    {
        $cacheKey = CreateCacheKeyHelper::forFind('findById', 'posts', $id, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $id) {
            $this->logger->debug('Cache MISS for post findById. Fetching from repository.', [
                'key' => $cacheKey,
                'id' => $id,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->findById($id, $filtersDTO);
        });
    }

    public function findPublishedById(string $id, FiltersDto $filtersDTO): Post
    {
        $cacheKey = CreateCacheKeyHelper::forFind('findPublishedById', 'posts', $id, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $id) {
            $this->logger->debug('Cache MISS for post findPublishedById. Fetching from repository.', [
                'key' => $cacheKey,
                'id' => $id,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->findPublishedById($id, $filtersDTO);
        });
    }

    public function create(CreatePostPersistenceDto $dto): Post
    {
        $this->clearCache('create');
        return $this->repository->create($dto);
    }

    public function update(Post $post, UpdatePostPersistenceDto $dto): bool
    {
        $this->clearCache('create');
        return $this->repository->update($post, $dto);
    }

    public function delete(Post $post): bool
    {
        $this->clearCache('delete', ['post_id' => $post->id]);
        return $this->repository->delete($post);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->repository->findBySlug($slug);
    }

    private function clearCache(string $reason, array $context = []): void
    {
        try {
            $this->cache->flush();
            $this->logger->info(
                'Post cache flushed.',
                array_merge($context, ['reason' => $reason, 'tag' => $this->cacheTag])
            );
        } catch (Exception $e) {
            $this->logger->error('Failed to flush post cache.', [
                'reason' => $reason,
                'tag' => $this->cacheTag,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
