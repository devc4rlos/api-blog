<?php

namespace App\Http\Requests\V1\User;

use App\Http\Requests\V1\BaseFormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $user = $this->route('user');
        $id = $user->id;
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
