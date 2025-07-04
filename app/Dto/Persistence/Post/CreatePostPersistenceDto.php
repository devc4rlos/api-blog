<?php

namespace App\Dto\Persistence\Post;

class CreatePostPersistenceDto
{
    private string $title;
    private string $description;
    private string $slug;
    private string $body;
    private ?string $imagePath;
    private string $status;

    public function __construct(
        string $title,
        string $description,
        string $slug,
        string $body,
        ?string $imagePath,
        string $status,
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->slug = $slug;
        $this->body = $body;
        $this->imagePath = $imagePath;
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

    public function imagePath(): ?string
    {
        return $this->imagePath;
    }

    public function status(): string
    {
        return $this->status;
    }
}
