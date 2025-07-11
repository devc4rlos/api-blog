<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
