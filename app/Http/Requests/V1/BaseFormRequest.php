<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

abstract class BaseFormRequest extends FormRequest
{
    protected array $exceptAttributesLog = [];

    protected function failedValidation(Validator $validator): void
    {
        Log::info('Requisition validation failed.', [
            'url' => $this->url(),
            'method' => $this->method(),
            'ip' => $this->ip(),
            'user_id' => $this->user()?->id,
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except($this->exceptAttributesLog),
        ]);
        parent::failedValidation($validator);
    }
}
