<?php

namespace Feature\Jobs;

use App\Dto\Persistence\PasswordReset\CreatePasswordResetPersistenceDto;
use App\Jobs\ProcessPasswordResetJob;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Repositories\PasswordReset\PasswordResetRepositoryInterface;
use App\Services\UserService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Tests\TestCase;

class ProcessPasswordResetJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     *
     */
    public function test_should_handle_password_reset_for_an_existing_user()
    {
        $user = User::factory()->create();

        $mockUserService = Mockery::mock(UserService::class);
        $mockRepository = Mockery::mock(PasswordResetRepositoryInterface::class);

        $mockUserService->shouldReceive('findByEmail')->once()->with($user->email)->andReturn($user);

        $mockRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($dto) use ($user) {
                return $dto instanceof CreatePasswordResetPersistenceDto && $dto->email() === $user->email;
            }));
        $mockRepository->shouldReceive('deleteCodesByEmail')->once()->with($user->email);

        Hash::shouldReceive('make')->once()->andReturn('hashed_code');

        Notification::fake();

        $job = new ProcessPasswordResetJob($user->email);
        $job->handle($mockUserService, $mockRepository);

        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class,
        );
    }


    public function test_does_nothing_for_a_non_existent_user(): void
    {
        $nonExistentEmail = 'ninguem@exemplo.com';

        $mockUserService = Mockery::mock(UserService::class);
        $mockRepository = Mockery::mock(PasswordResetRepositoryInterface::class);

        $mockUserService->shouldReceive('findByEmail')->once()->with($nonExistentEmail)->andReturn(null);

        $mockRepository->shouldReceive('create')->never();

        Hash::shouldReceive('make')->never();

        Notification::fake();

        $job = new ProcessPasswordResetJob($nonExistentEmail);
        $job->handle($mockUserService, $mockRepository);

        Notification::assertNothingSent();
    }
}
