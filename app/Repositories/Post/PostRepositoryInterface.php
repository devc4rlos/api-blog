<?php

namespace App\Repositories\Post;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function allPublished(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function allCommentsFromPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator;
    public function findById(string $id, FiltersDto $filtersDTO): Post;
    public function findPublishedById(string $id, FiltersDto $filtersDTO): Post;
    public function create(CreatePostPersistenceDto $dto): Post;
    public function update(Post $post, UpdatePostPersistenceDto $dto): bool;
    public function delete(Post $post): bool;
}
