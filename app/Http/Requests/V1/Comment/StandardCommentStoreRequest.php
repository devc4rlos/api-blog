<?php

namespace App\Http\Requests\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StandardCommentStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:500'],
            'post_id' => ['required', 'string', 'exists:posts,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
