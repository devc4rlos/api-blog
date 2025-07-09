<?php

namespace App\Http\Requests\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StandardCommentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => ['sometimes', 'string', 'max:500'],
            'post_id' => ['sometimes', 'string', 'exists:posts,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
