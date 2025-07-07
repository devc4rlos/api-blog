<?php

namespace App\Dto\Input\Comment;

class CreateCommentInputDto
{
    private string $body;
    private string $userId;
    private string $postId;

    public function __construct(
        string $body,
        string $userId,
        string $postId,
    )
    {
        $this->body = $body;
        $this->userId = $userId;
        $this->postId = $postId;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function postId(): string
    {
        return $this->postId;
    }
}
