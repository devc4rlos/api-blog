<?php

namespace App\Decorators\Post;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Events\PostChangedEvent;
use App\Helpers\CreateCacheKeyHelper;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
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

    public function allCommentsFromPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forFind('allCommentsFromPost', 'posts', $post, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $post) {
            $this->logger->debug('Cache MISS for post allCommentsFromPost. Fetching from repository.', [
                'key' => $cacheKey,
                'id' => $post,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->allCommentsFromPost($post, $filtersDTO);
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
        $post = $this->repository->create($dto);

        event(new PostChangedEvent($post));

        return $post;
    }

    public function update(Post $post, UpdatePostPersistenceDto $dto): bool
    {
        $result = $this->repository->update($post, $dto);

        if ($result) {
            event(new PostChangedEvent($post));
        }

        return $result;
    }

    public function delete(Post $post): bool
    {
        $result = $this->repository->delete($post);

        if ($result) {
            event(new PostChangedEvent($post));
        }

        return $result;
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->repository->findBySlug($slug);
    }
}
