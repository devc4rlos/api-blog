<?php

namespace App\Http\Requests\V1\PasswordReset;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
