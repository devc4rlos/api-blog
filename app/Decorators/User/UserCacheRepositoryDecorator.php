<?php

namespace App\Decorators\User;

use App\DTO\Filter\FiltersDTO;
use App\DTO\User\CreateUserPersistenceDTO;
use App\DTO\User\UpdateUserPersistenceDTO;
use App\Helpers\CreateCacheKeyHelper;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class UserCacheRepositoryDecorator implements UserRepositoryInterface
{
    private UserRepositoryInterface $repository;
    private CacheRepository $cache;
    private int $ttl = 15;
    private string $cacheTag = 'users';

    public function __construct(UserRepositoryInterface $repository, CacheFactory $cacheFactory)
    {
        $this->repository = $repository;
        $this->cache = $cacheFactory->store()->tags($this->cacheTag);
    }

    public function all(FiltersDTO $filtersDTO): LengthAwarePaginator
    {
        $cacheKey = CreateCacheKeyHelper::forIndex('users', $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($filtersDTO) {
            return $this->repository->all($filtersDTO);
        });
    }

    public function findById(int $id, FiltersDTO $filtersDTO): User
    {
        $cacheKey = CreateCacheKeyHelper::forFind('users', $id, $filtersDTO);

        return $this->cache->remember($cacheKey, now()->addMinutes($this->ttl), function () use ($id, $filtersDTO) {
            return $this->repository->findById($id, $filtersDTO);
        });
    }

    public function create(CreateUserPersistenceDTO $dto): User
    {
        $this->clearCache();
        return $this->repository->create($dto);
    }

    public function update(User $user, UpdateUserPersistenceDTO $dto): bool
    {
        $this->clearCache();
        return $this->repository->update($user, $dto);
    }

    public function delete(User $user): bool
    {
        $this->clearCache();
        return $this->repository->delete($user);
    }

    private function clearCache(): void
    {
        $this->cache->flush();
    }
}
