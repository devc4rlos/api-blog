<?php

namespace App\Http\Requests\V1\Post;

use App\Enums\PostStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:20000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status' => ['required', Rule::in(PostStatusEnum::cases())],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->get('slug');
        $title = $this->get('title');

        if ($slug === null && $title !== null) {
            $this->merge([
                'slug' => Str::slug($title),
            ]);
        } elseif ($slug) {
            $this->merge([
                'slug' => Str::slug($slug),
            ]);
        }
    }
}
