<?php

namespace App\Services;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\User\CreateUserInputDto;
use App\Dto\User\CreateUserPersistenceDto;
use App\Dto\User\UpdateUserInputDto;
use App\Dto\User\UpdateUserPersistenceDto;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Commons\FillableFromDtoTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    use FillableFromDtoTrait;

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->userRepository->all($filtersDTO);
    }

    public function findById(int $id, FiltersDto $filtersDTO): User
    {
        return $this->userRepository->findById($id, $filtersDTO);
    }

    public function create(CreateUserInputDto $userDTO): User
    {
        $user = new User([
            'name' => $userDTO->name(),
            'email' => $userDTO->email(),
            'password' => $userDTO->password(),
        ]);

        $userPersistenceDTO = new CreateUserPersistenceDto($user->name, $user->email, $user->password);
        return $this->userRepository->create($userPersistenceDTO);
    }

    public function update(User $user, UpdateUserInputDto $userDTO): bool
    {
        $userEntity = new User();

        $this->fill($userEntity, $userDTO, ['name', 'email']);

        return $this->userRepository->update($user, new UpdateUserPersistenceDto($userEntity->toArray()));
    }

    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
}
