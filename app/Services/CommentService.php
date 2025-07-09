<?php

namespace App\Services;

use App\Contracts\Services\CommentServiceInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Comment\CreateCommentInputDto;
use App\Dto\Input\Comment\UpdateCommentInputDto;
use App\Dto\Persistence\Comment\CreateCommentPersistenceDto;
use App\Dto\Persistence\Comment\UpdateCommentPersistenceDto;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService implements CommentServiceInterface
{
    private CommentRepositoryInterface $repository;

    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->repository->all($filtersDTO);
    }

    public function allFromUser(string $userId, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->repository->allFromUser($userId, $filtersDTO);
    }

    public function findById(string $id, FiltersDto $filtersDTO): Comment
    {
        return $this->repository->findById($id, $filtersDTO);
    }

    public function create(CreateCommentInputDto $commentDto): Comment
    {
        $dto = new CreateCommentPersistenceDto(
            body: $commentDto->body(),
            userId: $commentDto->userId(),
            postId: $commentDto->postId(),
        );

        return $this->repository->create($dto);
    }

    public function update(Comment $comment, UpdateCommentInputDto $commentDto): bool
    {
        $dto = new UpdateCommentPersistenceDto($commentDto->toArray());

        return $this->repository->update($comment, $dto);
    }

    public function delete(Comment $comment): bool
    {
        return $this->repository->delete($comment);
    }
}
