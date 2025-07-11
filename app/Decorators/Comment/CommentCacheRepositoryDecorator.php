<?php

namespace App\Decorators\Comment;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Comment\CreateCommentPersistenceDto;
use App\Dto\Persistence\Comment\UpdateCommentPersistenceDto;
use App\Events\CommentChangedEvent;
use App\Helpers\CreateCacheKeyHelper;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Log\LoggerInterface;

class CommentCacheRepositoryDecorator implements CommentRepositoryInterface
{
    private CommentRepositoryInterface $repository;
    private CacheRepository $cache;
    private int $ttl = 15;
    private string $cacheTag = 'comments';
    private LoggerInterface $logger;

    public function __construct(CommentRepositoryInterface $repository, CacheFactory $cacheFactory, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->cache = $cacheFactory->store()->tags($this->cacheTag);
        $this->logger = $logger;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forIndex('all', 'comments', $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey) {
            $this->logger->debug('Cache MISS for all comments. Fetching from repository.', [
                'key' => $cacheKey,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->all($filtersDTO);
        });
    }

    public function allFromUser(string $userId, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forIndex('allFromUser', 'comments', $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $userId) {
            $this->logger->debug('Cache MISS for all from user comments. Fetching from repository.', [
                'key' => $cacheKey,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->allFromUser($userId, $filtersDTO);
        });
    }

    public function findById(string $id, FiltersDto $filtersDTO): Comment
    {
        $cacheKey = CreateCacheKeyHelper::forFind('findById', 'comments', $id, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $id) {
            $this->logger->debug('Cache MISS for comments findById. Fetching from repository.', [
                'key' => $cacheKey,
                'id' => $id,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->findById($id, $filtersDTO);
        });
    }

    public function create(CreateCommentPersistenceDto $dto): Comment
    {
        $comment = $this->repository->create($dto);

        event(new CommentChangedEvent($comment));

        return $comment;
    }

    public function update(Comment $comment, UpdateCommentPersistenceDto $dto): bool
    {
        $result = $this->repository->update($comment, $dto);

        if ($result) {
            event(new CommentChangedEvent($comment));
        }

        return $result;
    }

    public function delete(Comment $comment): bool
    {
        $result = $this->repository->delete($comment);

        if ($result) {
            event(new CommentChangedEvent($comment));
        }

        return $result;
    }
}
