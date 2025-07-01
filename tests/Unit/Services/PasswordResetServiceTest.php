<?php

namespace Tests\Unit\Services;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Input\PasswordReset\PasswordResetInputDto;
use App\Jobs\ProcessPasswordResetJob;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use App\Services\PasswordResetService;
use DomainException;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PasswordResetServiceTest extends TestCase
{
    private MockInterface $repository;
    private MockInterface $userService;
    private PasswordResetService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(PasswordResetRepositoryInterface::class);
        $this->userService = Mockery::mock(UserServiceInterface::class);
        $this->service = new PasswordResetService($this->repository, $this->userService);
    }

    public function test_forgot_password_should_dispatch_password_reset_job()
    {
        Queue::fake();

        $this->service->forgotPassword(fake()->email());

        Queue::assertPushed(ProcessPasswordResetJob::class);
    }

    public function test_reset_password_succeeds_when_code_is_valid_and_unexpired()
    {
        $user = Mockery::mock(User::class);
        $dto = new PasswordResetInputDto(
            email: fake()->email(),
            code: 'valid_code',
            password: 'new_password_123'
        );

        $passwordReset = new PasswordReset();
        $passwordReset->token = bcrypt($dto->code());
        $passwordReset->created_at = now()->modify('-59 minutes');

        $this->repository->shouldReceive('findLastCodeByEmail')->once()->with($dto->email())->andReturn($passwordReset);
        $this->repository->shouldReceive('deleteCodesByEmail')->once();

        $this->userService->shouldReceive('findByEmail')->once()->with($dto->email())->andReturn($user);
        $this->userService->shouldReceive('updatePassword')->once();

        $this->service->resetPassword($dto);
    }

    public function test_reset_password_throws_exception_when_reset_code_record_not_found()
    {
        $this->expectException(DomainException::class);

        $dto = new PasswordResetInputDto(email: fake()->email(), code: 'any_code', password: 'any_password');
        $this->repository->shouldReceive('findLastCodeByEmail')->once()->with($dto->email())->andReturn(null);

        $this->service->resetPassword($dto);
    }

    public function test_reset_password_throws_exception_when_code_is_invalid()
    {
        $this->expectException(DomainException::class);

        $dto = new PasswordResetInputDto(email: fake()->email(), code: 'invalid_code', password: 'any_password');

        $passwordReset = new PasswordReset();
        $passwordReset->token = bcrypt('valid_code');
        $passwordReset->created_at = now()->modify('-30 minutes');

        $this->repository->shouldReceive('findLastCodeByEmail')->once()->with($dto->email())->andReturn($passwordReset);

        $this->service->resetPassword($dto);
    }

    public function test_reset_password_throws_exception_when_code_is_expired()
    {
        $this->expectException(DomainException::class);

        $dto = new PasswordResetInputDto(email: fake()->email(), code: 'valid_code', password: 'any_password');

        $passwordReset = new PasswordReset();
        $passwordReset->token = bcrypt($dto->code());
        $passwordReset->created_at = now()->modify('-61 minutes');

        $this->repository->shouldReceive('findLastCodeByEmail')->once()->with($dto->email())->andReturn($passwordReset);

        $this->service->resetPassword($dto);
    }
}
