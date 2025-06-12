<?php

namespace App\Repositories\User;

use App\DTO\Filter\FiltersDTO;
use App\DTO\User\CreateUserPersistenceDTO;
use App\DTO\User\UpdateUserPersistenceDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function all(FiltersDTO $filtersDTO): LengthAwarePaginator;
    public function findById(int $id, FiltersDTO $filtersDTO): User;
    public function create(CreateUserPersistenceDTO $dto): User;
    public function update(User $user, UpdateUserPersistenceDTO $dto): bool;
    public function delete(User $user): bool;
}
