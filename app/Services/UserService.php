<?php

namespace App\Services;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Input\User\CreateUserInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Dto\Persistence\User\CreateUserPersistenceDto;
use App\Dto\Persistence\User\UpdateUserPersistenceDto;
use App\Exceptions\BusinessRuleException;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
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
            'is_admin' => $userDTO->isAdmin(),
        ]);

        $userPersistenceDTO = new CreateUserPersistenceDto($user->name, $user->email, $user->password, $user->is_admin);
        return $this->userRepository->create($userPersistenceDTO);
    }

    public function update(User $user, UpdateUserInputDto $userDTO): bool
    {
        $userEntity = new User();

        $userEntity->fill($userDTO->toArray());

        return $this->userRepository->update($user, new UpdateUserPersistenceDto($userEntity->toArray()));
    }

    /**
     * @throws BusinessRuleException
     */
    public function delete(User $user): bool
    {
        if ($user->isAdmin() && $this->userRepository->countAdmins() <= 1) {
            throw new BusinessRuleException('You cannot remove the last administrator from the system.');
        }

        return $this->userRepository->delete($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
}
