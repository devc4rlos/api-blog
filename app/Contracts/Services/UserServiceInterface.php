<?php

namespace App\Contracts\Services;

use App\DTO\Filter\FiltersDTO;
use App\DTO\User\CreateUserInputDTO;
use App\DTO\User\UpdateUserInputDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function all(FiltersDTO $filtersDTO): LengthAwarePaginator;
    public function findById(int $id, FiltersDTO $filtersDTO): User;
    public function create(CreateUserInputDTO $userDTO): User;
    public function update(User $user, UpdateUserInputDTO $userDTO): bool;
    public function delete(User $user): bool;
}
