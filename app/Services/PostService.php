<?php

namespace App\Services;

use App\Contracts\Services\PostServiceInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Post\CreatePostInputDto;
use App\Dto\Input\Post\UpdatePostInputDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Enums\PostStatusEnum;
use App\Jobs\DeleteOldImagePostJob;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService implements PostServiceInterface
{
    private PostRepositoryInterface $repository;

    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->repository->all($filtersDTO);
    }

    public function allCommentsFromPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->repository->allCommentsFromPost($post, $filtersDTO);
    }

    public function allCommentsFromPublicPost(Post $post, FiltersDto $filtersDTO): LengthAwarePaginator
    {
        if ($post->status !== PostStatusEnum::PUBLISHED) {
            throw new ModelNotFoundException('The resource could not be found');
        }

        return $this->repository->allCommentsFromPost($post, $filtersDTO);
    }

    public function findById(string $id, FiltersDto $filtersDTO): Post
    {
        return $this->repository->findById($id, $filtersDTO);
    }

    public function create(CreatePostInputDto $postDto): Post
    {
        $uploadFile = $postDto->image();
        $imagePath = $uploadFile?->store('posts', 's3');

        $dto = new CreatePostPersistenceDto(
            title: $postDto->title(),
            description: $postDto->description(),
            slug: $postDto->slug(),
            body: $postDto->body(),
            imagePath: $imagePath,
            status: $postDto->status(),
        );

        return $this->repository->create($dto);
    }

    public function update(Post $post, UpdatePostInputDto $postDto): bool
    {
        $data = $postDto->toArray();
        $imagePathOld = $post->image_path;

        if ($image = $postDto->image()) {
            $imagePath = $image->store('posts', 's3');
            $data['image_path'] = $imagePath;
        }

        $dto = new UpdatePostPersistenceDto($data);

        $result = $this->repository->update($post, $dto);

        if ($result && $imagePathOld) {
            DeleteOldImagePostJob::dispatch($imagePathOld);
        }

        return $result;
    }

    public function delete(Post $post): bool
    {
        $imagePathOld = $post->image_path;
        $result = $this->repository->delete($post);

        if ($result && $imagePathOld) {
            DeleteOldImagePostJob::dispatch($imagePathOld);
        }

        return $result;
    }

    public function allPublished(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->repository->allPublished($filtersDTO);
    }

    public function findPublishedById(string $id, FiltersDto $filtersDTO): Post
    {
        return $this->repository->findPublishedById($id, $filtersDTO);
    }
}
