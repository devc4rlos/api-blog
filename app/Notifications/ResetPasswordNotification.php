<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $code;
    private string $name;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $expirationMinutes = config('auth.passwords.users.expire');

        return (new MailMessage)
            ->subject('Your password reset code')
            ->greeting('Hello, ' . $this->name)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Use the code below to reset your password:')
            ->line(new HtmlString('<div style="font-size: 24px; text-align: center; font-weight: bold; letter-spacing: 8px; margin: 20px 0;">' . $this->code . '</div>'))
            ->line('This code will expire in ' . $expirationMinutes . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
