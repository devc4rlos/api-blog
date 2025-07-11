<?php

namespace App\Repositories\Post;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\EloquentBuilderQueryGetter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPostRepository implements PostRepositoryInterface
{
    private Post $model;

    public function __construct()
    {
        $this->model = app(Post::class);
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

    public function allPublished(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model->published()->newQuery(),
            $filtersDTO,
            $this->model::pipelinesFindAll(),
            $this->model,
        );

        return $builder->all();
    }

    public function allCommentsFromPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        $modelComments = app(Comment::class);
        $builder = new EloquentBuilderQueryGetter(
            $post->comments()->newQuery(),
            $filtersDTO,
            $modelComments::pipelinesFindAll(),
            $modelComments,
        );

        return $builder->all();
    }

    public function findById(string $id, FiltersDto $filtersDTO): Post
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model::query(),
            $filtersDTO,
            $this->model::pipelinesFindOne(),
            $this->model,
        );

        return $builder->find($id);
    }

    public function findPublishedById(string $id, FiltersDto $filtersDTO): Post
    {
        $builder = new EloquentBuilderQueryGetter(
            $this->model->published()->newQuery(),
            $filtersDTO,
            $this->model::pipelinesFindOne(),
            $this->model,
        );

        return $builder->find($id);
    }

    public function create(CreatePostPersistenceDto $dto): Post
    {
        return $this->model->create([
            'title' => $dto->title(),
            'description' => $dto->description(),
            'slug' => $dto->slug(),
            'body' => $dto->body(),
            'image_path' => $dto->imagePath(),
            'status' => $dto->status(),
        ]);
    }

    public function update(Post $post, UpdatePostPersistenceDto $dto): bool
    {
        return $post->update($dto->toArray());
    }

    public function delete(Post $post): bool
    {
        return $post->delete() ?? false;
    }

    public function findBySlug(string $slug): ?Post
    {
        return Post::whereSlug($slug)->first() ?? null;
    }
}
