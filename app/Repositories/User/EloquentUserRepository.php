<?php

namespace App\Repositories\User;

use App\DTO\Filter\FiltersDTO;
use App\DTO\QueryPipelinesDTO;
use App\DTO\User\CreateUserPersistenceDTO;
use App\DTO\User\UpdateUserPersistenceDTO;
use App\Models\User;
use App\Repositories\EloquentBuilderQueryGetter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function all(FiltersDTO $filtersDTO): LengthAwarePaginator
    {
        $builder = new EloquentBuilderQueryGetter(
            User::query(),
            $filtersDTO,
            new QueryPipelinesDTO()
        );

        return $builder->all();
    }

    public function findById(int $id, FiltersDTO $filtersDTO): User
    {
        $builder = new EloquentBuilderQueryGetter(
            User::query(),
            $filtersDTO,
            new QueryPipelinesDTO()
        );

        return $builder->find($id);
    }

    public function create(CreateUserPersistenceDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);
    }

    public function update(User $user, UpdateUserPersistenceDTO $dto): bool
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
}
