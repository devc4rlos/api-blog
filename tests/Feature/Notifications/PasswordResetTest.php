<?php

namespace Feature\Notifications;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     */
    public function test_password_reset_notification_is_sent()
    {
        Notification::fake();

        $user = User::factory()->create();

        $code = 123456;
        $user->sendPasswordResetNotification($code);

        Notification::assertSentTo(
            [$user],
            ResetPasswordNotification::class
        );

        Notification::assertSentTo($user, function (ResetPasswordNotification $notification) use ($user, $code) {
            $mailData = $notification->toMail()->toArray();
            $this->assertStringContainsString('Hello, ' . $user->name, $mailData['greeting']);
            $this->assertStringContainsString($code, implode(' ', $mailData['introLines']));

            return true;
        });
    }
}
