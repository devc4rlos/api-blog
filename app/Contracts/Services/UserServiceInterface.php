<?php

namespace App\Contracts\Services;

use App\Dto\Filter\FiltersDto;
use App\Dto\Input\User\CreateUserInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Exceptions\BusinessRuleException;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator;

    public function findById(int $id, FiltersDto $filtersDTO): User;

    public function create(CreateUserInputDto $userDTO): User;

    public function update(User $user, UpdateUserInputDto $userDTO): bool;

    /**
     * @throws BusinessRuleException
     */
    public function delete(User $user): bool;

    public function findByEmail(string $email): ?User;
}
