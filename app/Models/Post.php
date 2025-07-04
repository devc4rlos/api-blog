<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'body',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => PostStatusEnum::class,
        ];
    }
}
