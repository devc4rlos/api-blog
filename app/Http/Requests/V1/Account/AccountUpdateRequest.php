<?php

namespace App\Http\Requests\V1\Account;

use App\Http\Requests\V1\BaseFormRequest;
use Illuminate\Validation\Rule;

class AccountUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $user = auth()->user();
        $id = $user->id;
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'is_admin' => ['sometimes', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
