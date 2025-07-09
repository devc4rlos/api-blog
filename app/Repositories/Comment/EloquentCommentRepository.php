<?php

namespace App\Repositories\Comment;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Comment\CreateCommentPersistenceDto;
use App\Dto\Persistence\Comment\UpdateCommentPersistenceDto;
use App\Models\Comment;
use App\Repositories\EloquentBuilderQueryGetter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    private Comment $model;

    public function __construct()
    {
        $this->model = app(Comment::class);
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model::query(),
            $filtersDTO,
            $this->model::pipelinesFindAll(),
            $this->model,
        );

        return $builder->all();
    }

    public function allFromUser(string $userId, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model::where('user_id', $userId)->newQuery(),
            $filtersDTO,
            $this->model::pipelinesFindAll(),
            $this->model,
        );

        return $builder->all();
    }

    public function findById(string $id, FiltersDto $filtersDTO): Comment
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model::query(),
            $filtersDTO,
            $this->model::pipelinesFindAll(),
            $this->model,
        );

        return $builder->find($id);
    }

    public function create(CreateCommentPersistenceDto $dto): Comment
    {
        return $this->model->create([
            'body' => $dto->body(),
            'user_id' => $dto->userId(),
            'post_id' => $dto->postId(),
        ]);
    }

    public function update(Comment $comment, UpdateCommentPersistenceDto $dto): bool
    {
        return $comment->update($dto->toArray());
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete() ?? false;
    }
}
