<?php

namespace App\Repositories\User;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\QueryPipeline\QueryPipelinesDto;
use App\Dto\Persistence\User\CreateUserPersistenceDto;
use App\Dto\Persistence\User\UpdateUserPersistenceDto;
use App\Models\User;
use App\Repositories\EloquentBuilderQueryGetter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $builder = new EloquentBuilderQueryGetter(
            User::query(),
            $filtersDTO,
            new QueryPipelinesDto()
        );

        return $builder->all();
    }

    public function findById(int $id, FiltersDto $filtersDTO): User
    {
        $builder = new EloquentBuilderQueryGetter(
            User::query(),
            $filtersDTO,
            new QueryPipelinesDto()
        );

        return $builder->find($id);
    }

    public function create(CreateUserPersistenceDto $dto): User
    {
        return User::create([
            'name' => $dto->name(),
            'email' => $dto->email(),
            'password' => $dto->password(),
            'is_admin' => $dto->isAdmin(),
        ]);
    }

    public function update(User $user, UpdateUserPersistenceDto $dto): bool
    {
        return $user->update($dto->toArray());
    }

    public function delete(User $user): bool
    {
        return $user->delete() ?? false;
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function countAdmins(): int
    {
        return User::where('is_admin', true)->count();
    }
}
