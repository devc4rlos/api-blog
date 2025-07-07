<?php

namespace App\Http\Requests\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => ['sometimes', 'string', 'max:500'],
            'post_id' => ['sometimes', 'string', 'exists:posts,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
