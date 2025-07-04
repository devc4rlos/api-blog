<?php

namespace App\Dto\Input\Post;

use App\Dto\Input\BaseUpdateInputDto;
use Illuminate\Http\UploadedFile;

class UpdatePostInputDto extends BaseUpdateInputDto
{
    public function image(): ?UploadedFile
    {
        return $this->changes['image'] ?? null;
    }

    public function allowedFields(): array
    {
        return ['title', 'description', 'slug', 'body', 'status'];
    }
}
