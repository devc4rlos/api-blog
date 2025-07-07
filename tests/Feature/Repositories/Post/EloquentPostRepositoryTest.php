<?php

namespace Tests\Feature\Repositories\Post;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Post\CreatePostPersistenceDto;
use App\Dto\Persistence\Post\UpdatePostPersistenceDto;
use App\Enums\PostStatusEnum;
use App\Models\Post;
use App\Repositories\Post\EloquentPostRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentPostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieved_all_posts()
    {
        $createdPosts = Post::factory()->count(10)->create();
        $repository = new EloquentPostRepository();

        $retrievedPosts = $repository->all(new FiltersDto());

        $createdIds = $createdPosts->pluck('id')->sort()->values();
        $retrievedIds = $retrievedPosts->pluck('id')->sort()->values();

        $this->assertCount(10, $retrievedPosts);
        $this->assertEquals($createdIds, $retrievedIds);
    }

    public function test_should_retrieved_all_posts_published()
    {
        Post::factory()->count(10)->create(['status' => PostStatusEnum::ARCHIVED->value]);
        $createdPosts = Post::factory()->count(10)->create(['status' => PostStatusEnum::PUBLISHED->value]);
        $repository = new EloquentPostRepository();

        $retrievedPosts = $repository->allPublished(new FiltersDto());

        $createdIds = $createdPosts->pluck('id')->sort()->values();
        $retrievedIds = $retrievedPosts->pluck('id')->sort()->values();

        $this->assertCount(10, $retrievedPosts);
        $this->assertEquals($createdIds, $retrievedIds);
    }

    public function test_should_find_post_id()
    {
        $postCreated = Post::factory()->create();
        $repository = new EloquentPostRepository();

        $post = $repository->findById($postCreated->id, new FiltersDto());

        $this->assertEquals($postCreated->id, $post->id);
    }

    public function test_should_find_post_id_published()
    {
        Post::factory()->count(10)->create(['status' => PostStatusEnum::ARCHIVED->value]);
        $postCreated = Post::factory()->create(['status' => PostStatusEnum::PUBLISHED->value]);
        $repository = new EloquentPostRepository();

        $post = $repository->findPublishedById($postCreated->id, new FiltersDto());

        $this->assertEquals($postCreated->id, $post->id);
    }

    public function test_should_return_model_not_found_exception_when_trying_to_access_unpublished_post()
    {
        $this->expectException(ModelNotFoundException::class);
        $postCreated = Post::factory()->create(['status' => PostStatusEnum::ARCHIVED->value]);
        $repository = new EloquentPostRepository();

        $repository->findPublishedById($postCreated->id, new FiltersDto());
    }

    public function test_should_create_post()
    {
        $dto = new CreatePostPersistenceDto(
            title: 'Title post',
            description: 'Description post',
            slug: 'slug-post',
            body: 'body-post',
            imagePath: 'image-post',
            status: PostStatusEnum::DRAFT->value
        );

        $repository = new EloquentPostRepository();
        $post = $repository->create($dto);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => $post->title, 'slug' => $post->slug]);
    }

    public function test_should_update_post()
    {
        $title = 'Title post';
        $postCreated = Post::factory()->create();
        $dto = new UpdatePostPersistenceDto(['title' => $title]);
        $repository = new EloquentPostRepository();

        $repository->update($postCreated, $dto);

        $this->assertDatabaseHas('posts', ['id' => $postCreated->id, 'title' => $title]);
    }

    public function test_should_delete_post()
    {
        $postCreated = Post::factory()->create();
        $repository = new EloquentPostRepository();

        $repository->delete($postCreated);
        $this->assertDatabaseMissing('posts', ['id' => $postCreated->id]);
    }

    public function test_should_find_post_by_slug()
    {
        $postCreated = Post::factory()->create([
            'slug' => 'slug-post',
        ]);

        $repository = new EloquentPostRepository();
        $post = $repository->findBySlug($postCreated->slug);
        $this->assertEquals($postCreated->slug, $post->slug);
    }


    public function test_should_return_null_when_search_post_with_invalid_slug()
    {
        Post::factory()->create();

        $repository = new EloquentPostRepository();
        $post = $repository->findBySlug('invalid-slug');
        $this->assertNull($post);
    }
}
