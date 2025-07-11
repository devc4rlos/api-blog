<?php

namespace App\Decorators\User;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\User\CreateUserPersistenceDto;
use App\Dto\Persistence\User\UpdateUserPersistenceDto;
use App\Events\UserChangedEvent;
use App\Helpers\CreateCacheKeyHelper;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
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
        $cacheKey = CreateCacheKeyHelper::forIndex('all', 'users', $filtersDTO);

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
        $cacheKey = CreateCacheKeyHelper::forFind('findById', 'users', $id, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO, $cacheKey, $id) {
            $this->logger->debug('Cache MISS for findById. Fetching from repository.', [
                'key' => $cacheKey,
                'id' => $id,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->findById($id, $filtersDTO);
        });
    }

    public function findByEmail(string $email): ?User
    {
        $cacheKey = CreateCacheKeyHelper::forFind('findByEmail', 'users', $email, new FiltersDto());

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($email, $cacheKey) {
            $this->logger->debug('Cache MISS for findByEmail. Fetching from repository.', [
                'key' => $cacheKey,
                'email' => $email,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->findByEmail($email);
        });
    }

    public function countAdmins(): int
    {
        $cacheKey = CreateCacheKeyHelper::forFind('countAdmins', 'users', 'count', new FiltersDto());

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($cacheKey) {
            $this->logger->debug('Cache MISS for countAdmins. Fetching from repository.', [
                'key' => $cacheKey,
                'tag' => $this->cacheTag
            ]);

            return $this->repository->countAdmins();
        });
    }

    public function create(CreateUserPersistenceDto $dto): User
    {
        $user = $this->repository->create($dto);

        event(new UserChangedEvent($user));

        return $user;
    }

    public function update(User $user, UpdateUserPersistenceDto $dto): bool
    {
        $result = $this->repository->update($user, $dto);

        if ($result) {
            event(new UserChangedEvent($user));
        }

        return $result;
    }

    public function delete(User $user): bool
    {
        $result = $this->repository->delete($user);

        if ($result) {
            event(new UserChangedEvent($user));
        }

        return $result;
    }
}
