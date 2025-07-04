<?php

namespace App\Dto\Input\Post;

use App\Enums\PostStatusEnum;
use Illuminate\Http\UploadedFile;

class CreatePostInputDto
{
    private string $title;
    private string $description;
    private string $slug;
    private string $body;
    private ?UploadedFile $image;
    private string $status;

    public function __construct(
        string $title,
        string $description,
        string $slug,
        string $body,
        ?UploadedFile $image,
        string $status = PostStatusEnum::DRAFT->value,
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->slug = $slug;
        $this->body = $body;
        $this->image = $image;
        $this->status = $status;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function image(): ?UploadedFile
    {
        return $this->image;
    }

    public function status(): string
    {
        return $this->status;
    }
}
