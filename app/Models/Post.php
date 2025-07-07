<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Post extends ModelCrud
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasUlids;

    protected $appends = ['image_url'];

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

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::disk('s3')->temporaryUrl(
                $this->image_path,
                now()->addMinutes(5)
            );
        }
        return null;
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', PostStatusEnum::PUBLISHED->value);
    }

    public function allowedSortBy(): array
    {
        return ['title', 'description', 'created_at'];
    }

    public function allowedFieldSearch(): array
    {
        return ['title', 'slug', 'description'];
    }
}
