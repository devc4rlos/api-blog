<?php

namespace Feature\Http\Controllers\V1;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/admin/comments/';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        $this->user = User::factory()->create(['is_admin' => true]);

        Sanctum::actingAs($this->user);
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

    public function test_should_retrieved_all_comments()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
    }

    #[DataProvider('provideFilters')]
    public function test_should_retrieved_all_comments_with_filters(string $query, string $sortBy, string $sortDirection)
    {
        Comment::factory()->count(10)->create();

        $response = $this->get($this->endpoint . $query);

        $retrievedIdComments = collect($response->json('data'))->pluck('id')->values()->toArray();
        $retrievedCreatedIdComments = Comment::orderBy($sortBy, $sortDirection)->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
        $this->assertSame($retrievedCreatedIdComments, $retrievedIdComments);
    }

    public function test_should_retrieved_all_comments_with_filter_search()
    {
        $body = 'Testing';

        $comment = Comment::factory()->create(['body' => $body]);

        $response = $this->get($this->endpoint . '?searchBy=body&search=' . $body);

        $retrievedIdComments = collect($response->json('data'))->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
        $this->assertTrue(in_array($comment->id, $retrievedIdComments));
    }

    public function test_should_retrieved_one_comment()
    {
        $comment = Comment::factory()->create();
        $response = $this->get($this->endpoint . $comment->id);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.show'), $response->json('message'));
    }

    public function test_should_create_new_comment()
    {
        $post = Post::factory()->create();
        $data = [
            'body' => fake()->sentence(),
            'post_id' => $post->id,
            'user_id' => $this->user->id,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/comment.store'), $response->json('message'));
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_should_update_comment(): void
    {
        $comment = Comment::factory()->create();
        $updatePost = Post::factory()->create();
        $updateUser = User::factory()->create();
        $data = [
            'body' => fake()->sentence(),
            'post_id' => $updatePost->id,
            'user_id' => $updateUser->id,
        ];
        $response = $this->patch($this->endpoint . $comment->id, $data);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.update'), $response->json('message'));
        $this->assertDatabaseHas('comments', array_merge(['id' => $comment->id], $data));
    }

    public function test_should_delete_comment()
    {
        $comment = Comment::factory()->create();
        $response = $this->delete($this->endpoint . $comment->id);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.destroy'), $response->json('message'));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_should_return_404_status_when_trying_to_delete_non_existent_comment()
    {
        $response = $this->delete($this->endpoint . '9999');
        $response->assertStatus(404);
    }

    public function test_should_return_404_status_when_trying_show_non_existent_comment()
    {
        $response = $this->get($this->endpoint . '9999');
        $response->assertStatus(404);
    }
}
