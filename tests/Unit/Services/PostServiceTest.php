<?php

namespace Tests\Unit\Services;

use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Post\CreatePostInputDto;
use App\Dto\Input\Post\UpdatePostInputDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Enums\PostStatusEnum;
use App\Jobs\DeleteOldImagePostJob;
use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
use App\Services\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    private MockInterface&PostRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(PostRepositoryInterface::class);
    }

    public function test_should_return_all_posts()
    {
        $lengthAwarePaginator = Mockery::mock(LengthAwarePaginator::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('all')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new PostService($this->repository);
        $service->all($filtersDTO);
    }

    public function test_should_return_all_posts_published()
    {
        $lengthAwarePaginator = Mockery::mock(LengthAwarePaginator::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('allPublished')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new PostService($this->repository);
        $service->allPublished($filtersDTO);
    }

    public function test_should_retrieve_post_comments_with_repository()
    {
        $lengthAwarePaginator = Mockery::mock(LengthAwarePaginator::class);
        $post = Mockery::mock(Post::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('allCommentsFromPost')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new PostService($this->repository);
        $service->allCommentsFromPost($post, $filtersDTO);
    }

    public function test_should_retrieve_all_comments_from_a_public_post()
    {
        $lengthAwarePaginator = Mockery::mock(LengthAwarePaginator::class);
        $post = Mockery::mock(Post::class)->makePartial();
        $post->status = PostStatusEnum::PUBLISHED;
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('allCommentsFromPost')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new PostService($this->repository);
        $service->allCommentsFromPublicPost($post, $filtersDTO);
    }

    public function test_should_return_model_not_found_exception_when_trying_to_access_comments_from_a_non_public_post()
    {
        $this->expectException(ModelNotFoundException::class);
        $post = Mockery::mock(Post::class)->makePartial();
        $post->status = PostStatusEnum::ARCHIVED->value;
        $filtersDTO = new FiltersDto();

        $service = new PostService($this->repository);
        $service->allCommentsFromPublicPost($post, $filtersDTO);
    }

    public function test_should_return_post_by_id()
    {
        $post = Mockery::mock(Post::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('findById')
            ->andReturn($post)
            ->once();

        $service = new PostService($this->repository);
        $service->findById(1, $filtersDTO);
    }

    public function test_should_return_post_published_by_id()
    {
        $post = Mockery::mock(Post::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('findPublishedById')
            ->andReturn($post)
            ->once();

        $service = new PostService($this->repository);
        $service->findPublishedById(1, $filtersDTO);
    }

    public function test_should_store_image_and_call_repository_with_correct_data(): void
    {
        Storage::fake('s3');
        $fakeImage = UploadedFile::fake()->image('cover.jpg');
        $title = 'Post title';

        $inputDto = new CreatePostInputDto(
            title: $title,
            description: fake()->text(),
            slug: fake()->slug(),
            body: fake()->sentence(),
            image: $fakeImage,
        );

        $this->repository
            ->expects('create')
            ->once()
            ->with(
                $this->callback(function (CreatePostPersistenceDto $dto) use ($title) {
                    $this->assertEquals($title, $dto->title());
                    $this->assertNotNull($dto->imagePath());
                    $this->assertStringContainsString('posts/', $dto->imagePath());
                    return true;
                })
            )
            ->andReturn(new Post(['id' => 1]));


        $service = new PostService($this->repository);
        $service->create($inputDto);

        $imagePath = Storage::disk('s3')->files('posts')[0];
        Storage::disk('s3')->assertExists($imagePath);
    }

    public function test_should_call_repository_without_image_path_if_no_image_is_provided(): void
    {
        Storage::fake('s3');

        $title = 'Post without image';
        $inputDto = new CreatePostInputDto(
            title: $title,
            description: fake()->text(),
            slug: fake()->slug(),
            body: fake()->sentence(),
            image: null,
        );

        $this->repository
            ->expects('create')
            ->once()
            ->with(
                $this->callback(function (CreatePostPersistenceDto $dto) use ($title) {
                    $this->assertNull($dto->imagePath());
                    $this->assertEquals($title, $dto->title());
                    return true;
                })
            )
            ->andReturn(new Post(['id' => 2]));

        $service = new PostService($this->repository);
        $service->create($inputDto);
    }

    public function test_should_update_post_data_without_a_new_image(): void
    {
        Storage::fake('s3');
        Queue::fake();

        $existingPost = Post::factory()->create(['image_path' => 'posts/old_image.jpg']);

        $titleUpdated = 'Post title updated';
        $inputDto = new UpdatePostInputDto([
            'title' => $titleUpdated,
            'image' => null
        ]);

        $this->repository
            ->expects('update')
            ->once()
            ->with(
                $this->equalTo($existingPost),
                $this->callback(function (UpdatePostPersistenceDto $dto) use ($titleUpdated) {
                    $this->assertEquals($titleUpdated, $dto->toArray()['title']);
                    $this->assertArrayNotHasKey('image_path', $dto->toArray());
                    return true;
                })
            )
            ->andReturn(true);

        $service = new PostService($this->repository);
        $result = $service->update($existingPost, $inputDto);

        $this->assertTrue($result);
        Queue::assertPushed(DeleteOldImagePostJob::class);
    }

    public function test_should_update_post_with_a_new_image_and_dispatch_deletion_job(): void
    {
        Storage::fake('s3');
        Queue::fake();

        $oldImagePath = 'posts/old_image.jpg';
        $existingPost = Post::factory()->create(['image_path' => $oldImagePath]);
        $newImage = UploadedFile::fake()->image('new_image.jpg');

        $inputDto = new UpdatePostInputDto([
            'title' => 'Updated title with new image',
            'image' => $newImage
        ]);

        $this->repository
            ->expects('update')
            ->once()
            ->with(
                $this->equalTo($existingPost),
                $this->callback(function (UpdatePostPersistenceDto $dto) {
                    $this->assertArrayHasKey('image_path', $dto->toArray());
                    $this->assertStringContainsString('posts/', $dto->toArray()['image_path']);
                    return true;
                })
            )
            ->andReturn(true);

        $service = new PostService($this->repository);
        $result = $service->update($existingPost, $inputDto);

        $this->assertTrue($result);
        Storage::disk('s3')->assertExists(Storage::disk('s3')->files('posts')[0]);

        Queue::assertPushed(DeleteOldImagePostJob::class, function ($job) use ($oldImagePath) {
            return $job->imagePath === $oldImagePath;
        });
    }

    public function test_should_not_dispatch_deletion_job_if_repository_update_fails(): void
    {
        Storage::fake('s3');
        Queue::fake();

        $existingPost = Post::factory()->create(['image_path' => 'posts/imagem_que_nao_sera_deletada.jpg']);
        $newImage = UploadedFile::fake()->image('imagem_nova.jpg');

        $inputDto = new UpdatePostInputDto([
            'title' => 'Qualquer', 'body' => 'Qualquer', 'image' => $newImage
        ]);

        $this->repository
            ->expects('update')
            ->once()
            ->andReturn(false);

        $service = new PostService($this->repository);
        $result = $service->update($existingPost, $inputDto);

        $this->assertFalse($result);
        Storage::disk('s3')->assertExists(Storage::disk('s3')->files('posts')[0]);
        Queue::assertNotPushed(DeleteOldImagePostJob::class);
    }

    public function test_should_delete_post()
    {
        Queue::fake();
        $post = Mockery::mock(Post::class)->makePartial();
        $post->image_path = fake()->filePath();

        $this->repository->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $service = new PostService($this->repository);
        $service->delete($post);
        Queue::assertPushed(DeleteOldImagePostJob::class);
    }

    public function test_should_not_dispatch_the_delete_job_if_the_repository_removal_fails()
    {
        Queue::fake();
        $post = Mockery::mock(Post::class)->makePartial();
        $post->image_path = fake()->filePath();

        $this->repository->shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $service = new PostService($this->repository);
        $service->delete($post);
        Queue::assertNotPushed(DeleteOldImagePostJob::class);
    }

    public function test_should_not_dispatch_the_delete_job_the_post_has_no_image()
    {
        Queue::fake();
        $post = Mockery::mock(Post::class)->makePartial();
        $post->image_path = null;

        $this->repository->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $service = new PostService($this->repository);
        $service->delete($post);
        Queue::assertNotPushed(DeleteOldImagePostJob::class);
    }
}
