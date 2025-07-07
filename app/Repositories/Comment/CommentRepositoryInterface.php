<?php

namespace App\Repositories\Comment;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Comment\CreateCommentPersistenceDto;
use App\Dto\Persistence\Comment\UpdateCommentPersistenceDto;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function findById(string $id, FiltersDto $filtersDTO): Comment;
    public function create(CreateCommentPersistenceDto $dto): Comment;
    public function update(Comment $comment, UpdateCommentPersistenceDto $dto): bool;
    public function delete(Comment $comment): bool;
}
