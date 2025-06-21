<?php

namespace App\Repositories\User;

use App\Dto\Filter\FiltersDto;
use App\Dto\User\CreateUserPersistenceDto;
use App\Dto\User\UpdateUserPersistenceDto;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function findById(int $id, FiltersDto $filtersDTO): User;
    public function create(CreateUserPersistenceDto $dto): User;
    public function update(User $user, UpdateUserPersistenceDto $dto): bool;
    public function delete(User $user): bool;
    public function findByEmail(string $email): ?User;
}
