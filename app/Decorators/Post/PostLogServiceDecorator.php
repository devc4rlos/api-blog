<?php

namespace App\Decorators\Post;

use App\Contracts\Services\PostServiceInterface;
use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Post\CreatePostInputDto;
use App\Dto\Input\Post\UpdatePostInputDto;
use App\Models\Post;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Log\LoggerInterface;

class PostLogServiceDecorator implements PostServiceInterface
{
    private PostServiceInterface $service;
    private LoggerInterface $logger;

    public function __construct(PostServiceInterface $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    public function all(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->service->all($filtersDTO);
    }

    public function allPublished(FiltersDto $filtersDTO): LengthAwarePaginator
    {
        return $this->service->allPublished($filtersDTO);
    }

    public function findById(string $id, FiltersDto $filtersDTO): Post
    {
        return $this->service->findById($id, $filtersDTO);
    }

    public function findPublishedById(string $id, FiltersDto $filtersDTO): Post
    {
        return $this->service->findPublishedById($id, $filtersDTO);
    }

    /**
     * @throws Exception
     */
    public function create(CreatePostInputDto $postDto): Post
    {
        $this->logger->info('Starting post creation process.', [
            'title' => $postDto->title(),
            'slug' => $postDto->slug(),
        ]);

        try {
            $createdPost = $this->service->create($postDto);

            $this->logger->info('Post created successfully.', ['post_id' => $createdPost->id]);

            return $createdPost;
        } catch(Exception $e) {
            $this->logger->error('Failed to create post.', [
                'input_data' => ['title' => $postDto->title(), 'slug' => $postDto->slug()],
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function update(Post $post, UpdatePostInputDto $postDto): bool
    {
        $this->logger->info('Starting post update process.', ['post_id' => $post->id]);
        try {
            $success = $this->service->update($post, $postDto);

            $this->logger->info('Post update process finished.', [
                'post_id' => $post->id,
                'success' => $success,
                'updated_data' => $postDto->toArray(),
            ]);

            return $success;
        } catch (Exception $e) {
            $this->logger->error('Failed to update post.', [
                'post_id' => $post->id,
                'input_data' => $postDto->toArray(),
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(Post $post): bool
    {
        $this->logger->info('Starting post deletion process.', ['post_id' => $post->id]);
        try {
            $success = $this->service->delete($post);

            $this->logger->info('Post deletion process finished.', [
                'post_id' => $post->id,
                'success' => $success
            ]);
            return $success;
        } catch (Exception $e) {
            $this->logger->error('Failed to delete post.', [
                'post_id' => $post->id,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
