<?php

namespace App\Contracts\Services;

use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Post\CreatePostInputDto;
use App\Dto\Input\Post\UpdatePostInputDto;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function allPublished(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function allCommentsFromPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator;
    public function allCommentsFromPublicPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator;
    public function findById(string $id, FiltersDto $filtersDTO): Post;
    public function findPublishedById(string $id, FiltersDto $filtersDTO): Post;
    public function create(CreatePostInputDto $postDto): Post;
    public function update(Post $post, UpdatePostInputDto $postDto): bool;
    public function delete(Post $post): bool;
}
