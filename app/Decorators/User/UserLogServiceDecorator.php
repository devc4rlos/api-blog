<?php

namespace App\Decorators\User;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Input\User\CreateUserInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Models\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Log\LoggerInterface;

class UserLogServiceDecorator implements UserServiceInterface
{
    private UserServiceInterface $service;
    private LoggerInterface $logger;

    public function __construct(UserServiceInterface $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->service->all($filtersDTO);
    }

    public function findById(int $id, FiltersDto $filtersDTO): User
    {
        return $this->service->findById($id, $filtersDTO);
    }

    /**
     * @throws Exception
     */
    public function create(CreateUserInputDto $userDTO): User
    {
        $this->logger->info('Starting user creation process.', [
            'name' => $userDTO->name(),
            'email' => $userDTO->email(),
        ]);

        try {
            $createdUser = $this->service->create($userDTO);

            $this->logger->info('User created successfully.', ['user_id' => $createdUser->id]);

            return $createdUser;
        } catch(Exception $e) {
            $this->logger->error('Failed to create user.', [
                'input_data' => ['name' => $userDTO->name(), 'email' => $userDTO->email()],
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update(User $user, UpdateUserInputDto $userDTO): bool
    {
        $this->logger->info('Starting user update process.', ['user_id' => $user->id]);
        try {
            $success = $this->service->update($user, $userDTO);

            $this->logger->info('User update process finished.', [
                'user_id' => $user->id,
                'success' => $success,
                'updated_data' => $userDTO->toArray(),
            ]);
        } catch (Exception $e) {
            $this->logger->error('Failed to update user.', [
                'user_id' => $user->id,
                'input_data' => $userDTO->toArray(),
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $success;
    }

    /**
     * @throws Exception
     */
    public function delete(User $user): bool
    {
        $this->logger->info('Starting user deletion process.', ['user_id' => $user->id]);
        try {
            $success = $this->service->delete($user);

            $this->logger->info('User deletion process finished.', [
                'user_id' => $user->id,
                'success' => $success
            ]);
            return $success;
        } catch (Exception $e) {
            $this->logger->error('Failed to delete user.', [
                'user_id' => $user->id,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function findByEmail(string $email): ?User
    {
        return $this->service->findByEmail($email);
    }
}
