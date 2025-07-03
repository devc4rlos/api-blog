<?php

namespace App\Repositories\User;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\User\CreateUserPersistenceDto;
use App\Dto\Persistence\User\UpdateUserPersistenceDto;
use App\Models\User;
use App\Repositories\EloquentBuilderQueryGetter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    private User $model;

    public function __construct()
    {
        $this->model = app(User::class);
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model::query(),
            $filtersDTO,
            $this->model::pipelinesFindAll(),
            $this->model,
        );

        return $builder->all();
    }

    public function findById(int $id, FiltersDto $filtersDTO): User
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model::query(),
            $filtersDTO,
            $this->model::pipelinesFindOne(),
            $this->model,
        );

        return $builder->find($id);
    }

    public function create(CreateUserPersistenceDto $dto): User
    {
        return $this->model::create([
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
        return $this->model::where('email', $email)->first();
    }

    public function countAdmins(): int
    {
        return $this->model::where('is_admin', true)->count();
    }
}
