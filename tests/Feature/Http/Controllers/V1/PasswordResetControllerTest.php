<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Jobs\ProcessPasswordResetJob;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpointForgotPassword = '/v1/forgot-password';
    private string $endpointResetPassword = '/v1/reset-password';

    public function test_forgot_password_dispatches_job_for_valid_email(): void
    {
        Queue::fake();
        $user = User::factory()->create();

        $response = $this->postJson($this->endpointForgotPassword, [
            'email' => $user->email,
        ]);

        $response->assertOk();
        $response->assertJson(['message' => __('controllers/password-reset.forgot-password')]);
        Queue::assertPushed(ProcessPasswordResetJob::class);
    }

    public function test_reset_password_succeeds_with_valid_data(): void
    {
        $user = User::factory()->create();
        $newPassword = 'new-strong-password-123';
        $code = 'VALID_CODE';

        PasswordReset::factory()->create([
            'email' => $user->email,
            'token' => bcrypt($code),
        ]);

        $response = $this->postJson($this->endpointResetPassword, [
            'email' => $user->email,
            'code' => $code,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertOk();
        $user->refresh();
        $response->assertJson(['message' => __('controllers/password-reset.reset-password-success')]);
        $this->assertTrue(password_verify($newPassword, $user->password));
    }

    public function test_reset_password_fails_for_non_existent_email(): void
    {
        $payload = [
            'email' => 'non.existent@email.com',
            'code' => 'any_code',
            'password' => 'any_password',
        ];

        $response = $this->postJson($this->endpointResetPassword, $payload);

        $response->assertJson(['message' => __('controllers/password-reset.reset-password-failure')]);
        $response->assertUnprocessable();
    }

    public function test_reset_password_fails_with_invalid_code(): void
    {
        $user = User::factory()->create();
        $newPassword = 'new-strong-password-123';

        PasswordReset::factory()->create([
            'email' => $user->email,
            'token' => bcrypt('CORRECT_CODE_FROM_DB'),
        ]);

        $response = $this->postJson($this->endpointResetPassword, [
            'email' => $user->email,
            'code' => 'INVALID_CODE_SUBMITTED',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertUnprocessable();
        $response->assertJson(['message' => __('controllers/password-reset.reset-password-failure')]);
        $this->assertFalse(password_verify($newPassword, $user->refresh()->password));
    }

    public function test_reset_password_fails_when_code_is_expired(): void
    {
        $user = User::factory()->create();
        $newPassword = 'new-strong-password-123';
        $code = 'EXPIRED_CODE';

        PasswordReset::factory()->create([
            'email' => $user->email,
            'token' => bcrypt($code),
            'created_at' => now()->subHours(2),
        ]);

        $response = $this->postJson($this->endpointResetPassword, [
            'email' => $user->email,
            'code' => $code,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertUnprocessable();
        $response->assertJson(['message' => __('controllers/password-reset.reset-password-failure')]);
        $this->assertFalse(password_verify($newPassword, $user->refresh()->password));
    }
}
