<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends ModelCrud
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'body',
        'post_id',
        'user_id',
    ];

    public function allowedFieldSearch(): array
    {
        return ['body'];
    }
}
