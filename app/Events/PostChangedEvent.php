<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;

class PostChangedEvent
{
    use Dispatchable;

    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
