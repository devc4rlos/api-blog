<?php

namespace App\Services;

use App\Contracts\Services\PasswordResetServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Dto\Input\PasswordReset\PasswordResetInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Jobs\ProcessPasswordResetJob;
use App\Models\PasswordReset;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Hash;

class PasswordResetService implements PasswordResetServiceInterface
{
    private PasswordResetRepositoryInterface $repository;
    private UserServiceInterface $userService;

    public function __construct(PasswordResetRepositoryInterface $service, UserServiceInterface $userService)
    {
        $this->repository = $service;
        $this->userService = $userService;
    }

    public function forgotPassword(string $email): void
    {
        ProcessPasswordResetJob::dispatch($email);
    }

    public function resetPassword(PasswordResetInputDto $passwordResetDto): void
    {
        $passwordReset = $this->repository->findLastCodeByEmail($passwordResetDto->email());

        if (!$passwordReset || !$this->checkCodeValidity($passwordReset, $passwordResetDto->code()) || $this->checkCodeExpired($passwordReset)) {
            throw new DomainException('Code invalid');
        }

        $user = $this->userService->findByEmail($passwordResetDto->email());
        $this->userService->updatePassword($user, $passwordResetDto->password());

        $this->repository->deleteCodesByEmail($passwordResetDto->email());
    }

    private function checkCodeValidity(PasswordReset $passwordReset, string $code): bool
    {
        return Hash::check($code, $passwordReset->token);
    }

    private function checkCodeExpired(PasswordReset $passwordReset): bool
    {
        $expiredAtInMinutes = config('auth.passwords.users.expire');
        return abs(now()->diffInMinutes($passwordReset->created_at)) > $expiredAtInMinutes;
    }
}
