<?php

namespace App\Dto\Input\Comment;

use App\Dto\Input\BaseUpdateInputDto;

class UpdateCommentInputDto extends BaseUpdateInputDto
{
    public function allowedFields(): array
    {
        return ['body', 'post_id', 'user_id'];
    }
}
