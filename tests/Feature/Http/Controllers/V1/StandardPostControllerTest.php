<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Enums\PostStatusEnum;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StandardPostControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/posts/';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    public static function provideFilters(): array
    {
        return [
            'Default' => ['', 'created_at', 'desc'],
            'Sort by invalid' => ['?sortBy=invalid', 'created_at', 'desc'],
            'Sort direction invalid' => ['?sortDirection=invalid', 'created_at', 'desc'],
            'Sort by and sort direction' => ['?sortBy=title&sortDirection=asc', 'title', 'asc'],
        ];
    }

    public function test_should_retrieved_all_posts_published()
    {
        Post::factory()->count(10)->create(['status' => PostStatusEnum::ARCHIVED->value]);
        Post::factory()->count(5)->create(['status' => PostStatusEnum::PUBLISHED->value]);
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $this->assertSame(__('controllers/post.index'), $response->json('message'));
    }

    #[DataProvider('provideFilters')]
    public function test_should_retrieved_all_posts_with_filters(string $query, string $sortBy, string $sortDirection)
    {
        Post::factory()->count(10)->create();

        $response = $this->get($this->endpoint . $query);

        $retrievedIdPosts = collect($response->json('data'))->pluck('id')->values()->toArray();
        $retrievedCreatedIdPosts = Post::published()->orderBy($sortBy, $sortDirection)->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.index'), $response->json('message'));
        $this->assertSame($retrievedCreatedIdPosts, $retrievedIdPosts);
    }

    public function test_should_retrieved_all_posts_with_filter_search()
    {
        $title = 'Testing';

        $post = Post::factory()->create(['title' => $title, 'status' => PostStatusEnum::PUBLISHED->value]);

        $response = $this->get($this->endpoint . '?searchBy=title&search=' . $title);

        $retrievedIdPosts = collect($response->json('data'))->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.index'), $response->json('message'));
        $this->assertTrue(in_array($post->id, $retrievedIdPosts));
    }

    public function test_should_return_post_published_passing_slug_on_route()
    {
        $post = Post::factory()->create(['status' => PostStatusEnum::PUBLISHED->value]);
        $response = $this->get($this->endpoint . $post->slug);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/post.show'), $response->json('message'));
    }

    public function test_should_return_all_comments_from_a_public_post()
    {
        $post = Post::factory()->create(['status' => PostStatusEnum::PUBLISHED->value]);
        $createdComments = Comment::factory()->count(10)->create(['post_id' => $post->id]);
        $response = $this->get($this->endpoint . $post->slug . '/comments');

        $retrievedIdComments = collect($response->json('data'))->pluck('id')->values()->toArray();
        $idComments = collect($createdComments)->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertEquals(10, $post->comments->count());
        $this->assertEquals($retrievedIdComments, $idComments);
        $this->assertSame(__('controllers/post.comments'), $response->json('message'));
    }

    public function test_should_return_404_status_when_trying_to_access_comments_on_a_post_that_is_not_public()
    {
        $post = Post::factory()->create(['status' => PostStatusEnum::ARCHIVED->value]);
        $response = $this->get($this->endpoint . $post->slug . '/comments');

        $response->assertStatus(404);
    }

    public function test_should_return_404_status_when_trying_to_access_post_that_is_not_public()
    {
        $post = Post::factory()->create(['status' => PostStatusEnum::ARCHIVED->value]);
        $response = $this->get($this->endpoint . $post->slug);

        $response->assertStatus(404);
    }

    public function test_should_return_404_status_when_trying_to_access_post_with_invalid_slug()
    {
        $response = $this->get($this->endpoint . fake()->uuid());

        $response->assertStatus(404);
    }
}
