<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Enums\PostStatusEnum;
use App\Jobs\DeleteOldImagePostJob;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/posts/';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        Queue::fake();
        Sanctum::actingAs(User::factory()->create(['is_admin' => true]));
    }

    public static function provideFilters(): array
    {
        return [
            'Default' => ['', 'created_at', 'desc'],
            'Sort by invalid' => ['?sortBy=invalid', 'created_at', 'desc'],
            'Sort direction invalid' => ['?sortDirection=invalid', 'created_at', 'desc'],
            'Sort by and sort direction' => ['?sortBy=name&sortDirection=asc', 'name', 'asc'],
        ];
    }

    public function test_should_retrieved_all_posts()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.index'), $response->json('message'));
    }

    #[DataProvider('provideFilters')]
    public function test_should_retrieved_all_posts_with_filters(string $query, string $sortBy, string $sortDirection)
    {
        Post::factory()->count(10)->create();

        $response = $this->get($this->endpoint . $query);

        $retrievedIdPosts = collect($response->json('data'))->pluck('id')->values()->toArray();
        $retrievedCreatedIdPosts = Post::orderBy($sortBy, $sortDirection)->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.index'), $response->json('message'));
        $this->assertSame($retrievedCreatedIdPosts, $retrievedIdPosts);
    }

    public function test_should_retrieved_all_posts_with_filter_search()
    {
        $title = 'Testing';

        $post = Post::factory()->create(['title' => $title]);

        $response = $this->get($this->endpoint . '?searchBy=title&search=' . $title);

        $retrievedIdPosts = collect($response->json('data'))->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.index'), $response->json('message'));
        $this->assertTrue(in_array($post->id, $retrievedIdPosts));
    }

    public function test_should_retrieved_one_post()
    {
        $post = Post::factory()->create();
        $response = $this->get($this->endpoint . $post->id);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.show'), $response->json('message'));
    }

    public function test_should_create_new_post()
    {
        $data = [
            'title' => fake()->title(),
            'description' =>fake()->text(),
            'body' => fake()->sentence(),
            'status' => PostStatusEnum::DRAFT->value,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/post.store'), $response->json('message'));
        $this->assertDatabaseHas('posts', $data);
    }

    public function test_should_fail_when_trying_to_create_post_with_existing_slug()
    {
        $slug = fake()->slug();
        Post::factory()->create(['slug' => $slug]);
        $data = [
            'title' => fake()->title(),
            'description' =>fake()->text(),
            'slug' => $slug,
            'body' => fake()->sentence(),
            'status' => PostStatusEnum::DRAFT->value,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(422);
    }

    public function test_should_update_a_post_successfully_with_a_new_image(): void
    {
        $oldImagePath = 'posts/old-image.jpg';
        $post = Post::factory()->create(['image_path' => $oldImagePath]);

        $newImage = UploadedFile::fake()->image('new-image.png', 800, 600);
        $updateData = [
            'title' => 'Updated Post Title',
            'body' => 'This is the new body of the post',
            'image' => $newImage,
        ];

        $response = $this->patch($this->endpoint . $post->id, $updateData);

        $response->assertOk();
        $response->assertJson([
            'message' => __('controllers/post.update'),
        ]);

        $post->refresh();
        $this->assertEquals($updateData['title'], $post->title);
        $this->assertNotEquals($oldImagePath, $post->image_path);

        Storage::disk('s3')->assertExists($post->image_path);

        Queue::assertPushed(DeleteOldImagePostJob::class, function ($job) use ($oldImagePath) {
            return $job->imagePath === $oldImagePath;
        });
    }

    public function test_should_return_a_validation_error_if_title_is_missing(): void
    {
        $post = Post::factory()->create();

        $updateData = [
            'title' => '',
            'body' => 'Invalid Body',
        ];

        $response = $this->patch($this->endpoint . $post->id, $updateData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_should_fail_when_trying_to_update_post_with_existing_slug()
    {
        $title = 'Post title';
        $slug = fake()->slug();
        $postFirst = Post::factory()->create(['slug' => $slug]);
        $post = Post::factory()->create();

        $data = [
            'title' => $title,
            'slug' => $postFirst->slug,
        ];

        $response = $this->put($this->endpoint . $post->id, $data);

        $response->assertStatus(422);
    }

    public function test_should_update_post()
    {
        $post = Post::factory()->create();
        $title = 'Post title';
        $data = [
            'title' => $title,
        ];
        $response = $this->put($this->endpoint . $post->id, $data);
        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.update'), $response->json('message'));
        $this->assertDatabaseHas('posts', $data);
    }

    public function test_should_delete_post()
    {
        $post = Post::factory()->create(['image_path' => null]);
        $response = $this->delete($this->endpoint . $post->id);
        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.destroy'), $response->json('message'));
        Queue::assertNotPushed(DeleteOldImagePostJob::class);
    }

    public function test_should_delete_image_when_removing_post()
    {
        $post = Post::factory()->create();
        $response = $this->delete($this->endpoint . $post->id);
        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.destroy'), $response->json('message'));
        Queue::assertPushed(DeleteOldImagePostJob::class);
    }

    public function test_should_return_404_status_when_trying_to_delete_non_existent_post()
    {
        $response = $this->delete($this->endpoint . '9999');
        $response->assertStatus(404);
    }

    public function test_should_return_404_status_when_trying_show_non_existent_post()
    {
        $response = $this->get($this->endpoint . '9999');
        $response->assertStatus(404);
    }
}
