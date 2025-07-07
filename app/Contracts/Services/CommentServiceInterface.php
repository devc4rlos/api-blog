<?php

namespace App\Contracts\Services;

use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Comment\CreateCommentInputDto;
use App\Dto\Input\Comment\UpdateCommentInputDto;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentServiceInterface
{
    public function all(FiltersDto $filtersDTO): LengthAwarePaginator;
    public function findById(string $id, FiltersDto $filtersDTO): Comment;
    public function create(CreateCommentInputDto $commentDto): Comment;
    public function update(Comment $comment, UpdateCommentInputDto $commentDto): bool;
    public function delete(Comment $comment): bool;
}
