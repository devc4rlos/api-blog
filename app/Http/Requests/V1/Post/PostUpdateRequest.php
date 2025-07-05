<?php

namespace App\Http\Requests\V1\Post;

use App\Enums\PostStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:posts,slug,' . $this->route('post')->id],
            'body' => ['sometimes', 'string', 'max:20000'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status' => ['sometimes', Rule::in(PostStatusEnum::cases())],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
