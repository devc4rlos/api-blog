<?php

namespace App\Jobs;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Persistence\PasswordReset\CreatePasswordResetPersistenceDto;
use App\Helpers\GenerateCodeHelper;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use Hash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPasswordResetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $email) {}

    public function handle(UserServiceInterface $userService, PasswordResetRepositoryInterface $repository): void
    {
        $user = $userService->findByEmail($this->email);

        if ($user) {
            $code = GenerateCodeHelper::generate();
            $dto = new CreatePasswordResetPersistenceDto($this->email, Hash::make($code));
            $repository->deleteCodesByEmail($this->email);
            $repository->create($dto);
            $user->sendPasswordResetNotification($code);
        }
    }
}
