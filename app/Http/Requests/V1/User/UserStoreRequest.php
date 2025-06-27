<?php

namespace App\Http\Requests\V1\User;

use App\Http\Requests\V1\BaseFormRequest;

class UserStoreRequest extends BaseFormRequest
{
    protected array $exceptAttributesLog = ['password', 'password_confirmation', 'token'];

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'max:255'],
            'is_admin' => ['sometimes', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
