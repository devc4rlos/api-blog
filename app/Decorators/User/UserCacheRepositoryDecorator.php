<?php

namespace App\Decorators\User;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\User\CreateUserPersistenceDto;
use App\Dto\Persistence\User\UpdateUserPersistenceDto;
use App\Helpers\CreateCacheKeyHelper;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Exception;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Log\LoggerInterface;

class UserCacheRepositoryDecorator implements UserRepositoryInterface
{
    private UserRepositoryInterface $repository;
    private CacheRepository $cache;
    private int $ttl = 15;
    private string $cacheTag = 'users';
    private LoggerInterface $logger;

    public function __construct(UserRepositoryInterface $repository, CacheFactory $cacheFactory, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->cache = $cacheFactory->store()->tags($this->cacheTag);
        $this->logger = $logger;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forIndex('users', $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey) {
            $this->logger->debug('Cache MISS for users all. Fetching from repository.', [
                'key' => $cacheKey,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->all($filtersDTO);
        });
    }

    public function findById(int $id, FiltersDto $filtersDTO): User
    {
        $cacheKey = CreateCacheKeyHelper::forFind('users', $id, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $id) {
            $this->logger->debug('Cache MISS for findById. Fetching from repository.', [
                'key' => $cacheKey,
                'id' => $id,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->findById($id, $filtersDTO);
        });
    }

    public function create(CreateUserPersistenceDto $dto): User
    {
        $this->clearCache('create');
        return $this->repository->create($dto);
    }

    public function update(User $user, UpdateUserPersistenceDto $dto): bool
    {
        $this->clearCache('update', ['user_id' => $user->id]);
        return $this->repository->update($user, $dto);
    }

    public function delete(User $user): bool
    {
        $this->clearCache('delete', ['user_id' => $user->id]);
        return $this->repository->delete($user);
    }

    private function clearCache(string $reason, array $context = []): void
    {
        try {
            $this->cache->flush();
            $this->logger->info(
                'User cache flushed.',
                array_merge($context, ['reason' => $reason, 'tag' => $this->cacheTag])
            );
        } catch (Exception $e) {
            $this->logger->error('Failed to flush user cache.', [
                'reason' => $reason,
                'tag' => $this->cacheTag ,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository->findByEmail($email);
    }
}
