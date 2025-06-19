<?php

namespace App\Services;

use App\Contracts\Services\UserServiceInterface;
use App\DTO\Filter\FiltersDTO;
use App\DTO\User\CreateUserInputDTO;
use App\DTO\User\CreateUserPersistenceDTO;
use App\DTO\User\UpdateUserInputDTO;
use App\DTO\User\UpdateUserPersistenceDTO;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Commons\FillableFromDTOTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    use FillableFromDTOTrait;

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(FiltersDTO $filtersDTO): LengthAwarePaginator
    {
        return $this->userRepository->all($filtersDTO);
    }

    public function findById(int $id, FiltersDTO $filtersDTO): User
    {
        return $this->userRepository->findById($id, $filtersDTO);
    }

    public function create(CreateUserInputDTO $userDTO): User
    {
        $user = new User([
            'name' => $userDTO->name(),
            'email' => $userDTO->email(),
            'password' => $userDTO->password(),
        ]);

        $userPersistenceDTO = new CreateUserPersistenceDTO($user->name, $user->email, $user->password);
        return $this->userRepository->create($userPersistenceDTO);
    }

    public function update(User $user, UpdateUserInputDTO $userDTO): bool
    {
        $userEntity = new User();

        $this->fill($userEntity, $userDTO, ['name', 'email']);

        return $this->userRepository->update($user, new UpdateUserPersistenceDTO($userEntity->toArray()));
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
