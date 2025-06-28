<?php

namespace Tests\Feature\Repositories\PasswordReset;

use App\Dto\Persistence\PasswordReset\CreatePasswordResetPersistenceDto;
use App\Models\PasswordReset;
use App\Repositories\PasswordReset\EloquentPasswordResetRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentPasswordResetRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_find_password_reset_by_email()
    {
        $email = fake()->email();

        $resetRecord = PasswordReset::factory()->create([
            'email' => $email,
        ])->firstOrFail();

        $repository = new EloquentPasswordResetRepository();
        $recordFound = $repository->findLastCodeByEmail($email);

        $this->assertSame($resetRecord->email, $recordFound->email);
        $this->assertSame($resetRecord->token, $recordFound->token);
    }

    public function test_should_create_password_reset()
    {
        $dto = new CreatePasswordResetPersistenceDto(fake()->email(), fake()->password());
        $repository = new EloquentPasswordResetRepository();

        $repository->create($dto);

        $this->assertDatabaseCount('password_reset_tokens', 1);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $dto->email(), 'token' => $dto->code()]);
    }

    public function test_should_delete_password_reset()
    {
        $resetRecord = PasswordReset::factory()->create()->firstOrFail();
        $repository = new EloquentPasswordResetRepository();

        $repository->delete($resetRecord->token);

        $this->assertDatabaseCount('password_reset_tokens', 0);
        $this->assertDatabaseMissing('password_reset_tokens', ['id' => $resetRecord->id]);
    }
}
