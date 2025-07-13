<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StandardCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/v1/comments/';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);
    }

    public static function provideFilters(): array
    {
        return [
            'Default' => ['', 'created_at', 'desc'],
            'Sort by invalid' => ['?sortBy=invalid', 'created_at', 'desc'],
            'Sort direction invalid' => ['?sortDirection=invalid', 'created_at', 'desc'],
            'Sort by and sort direction' => ['?sortBy=created_at&sortDirection=asc', 'created_at', 'asc'],
        ];
    }

    public function test_should_retrieved_all_my_comments()
    {
        $response = $this->get($this->endpoint);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
    }

    #[DataProvider('provideFilters')]
    public function test_should_retrieved_all_my_comments_with_filters(string $query, string $sortBy, string $sortDirection)
    {
        Comment::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->get($this->endpoint . $query);

        $retrievedIdComments = collect($response->json('data'))->pluck('id')->values()->toArray();
        $retrievedCreatedIdComments = Comment::where('user_id', $this->user->id)->orderBy($sortBy, $sortDirection)->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
        $this->assertSame($retrievedCreatedIdComments, $retrievedIdComments);
    }

    public function test_should_return_only_the_comments_of_the_logged_in_user()
    {
        Comment::factory()->count(5)->create();
        Comment::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->get($this->endpoint);

        $retrievedIdComments = collect($response->json('data'))->pluck('id')->values()->sort()->toArray();
        $retrievedCreatedIdComments = Comment::where('user_id', $this->user->id)->pluck('id')->values()->sort()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
        $this->assertSame($retrievedCreatedIdComments, $retrievedIdComments);
    }

    public function test_should_retrieved_all_my_comments_with_filter_search()
    {
        $body = 'Testing';

        $comment = Comment::factory()->create(['body' => $body, 'user_id' => $this->user->id]);

        $response = $this->get($this->endpoint . '?searchBy=body&search=' . $body);

        $retrievedIdComments = collect($response->json('data'))->pluck('id')->values()->toArray();

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.index'), $response->json('message'));
        $this->assertTrue(in_array($comment->id, $retrievedIdComments));
    }

    public function test_should_retrieved_one_comment()
    {
        $comment = Comment::factory()->create(['user_id' => $this->user->id]);
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
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/comment.store'), $response->json('message'));
        $this->assertDatabaseHas('comments', ['user_id' => $this->user->id, ...$data]);
    }

    public function test_should_not_allow_creating_comment_with_another_user_id()
    {
        $post = Post::factory()->create();
        $data = [
            'body' => fake()->sentence(),
            'post_id' => $post->id,
            'user_id' => User::factory()->create()->id,
        ];
        $response = $this->post($this->endpoint, $data);

        $response->assertStatus(201);
        $this->assertSame(__('controllers/comment.store'), $response->json('message'));
        $this->assertDatabaseHas('comments', ['user_id' => $this->user->id, 'post_id' => $post->id]);
    }

    public function test_should_update_comment(): void
    {
        $comment = Comment::factory()->create(['user_id' => $this->user->id]);
        $updatePost = Post::factory()->create();
        $data = [
            'body' => fake()->sentence(),
            'post_id' => $updatePost->id,
        ];
        $response = $this->patch($this->endpoint . $comment->id, $data);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.update'), $response->json('message'));
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'user_id' => $this->user->id, ...$data]);
    }

    public function test_should_not_update_user_id_when_updating_comment(): void
    {
        $comment = Comment::factory()->create(['user_id' => $this->user->id]);
        $body = fake()->sentence();
        $data = [
            'body' => $body,
            'user_id' => User::factory()->create()->id,
        ];
        $response = $this->patch($this->endpoint . $comment->id, $data);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.update'), $response->json('message'));
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'user_id' => $this->user->id, 'body' => $body]);
    }

    public function test_should_delete_comment()
    {
        $comment = Comment::factory()->create(['user_id' => $this->user->id]);
        $response = $this->delete($this->endpoint . $comment->id);

        $response->assertStatus(200);
        $this->assertSame(__('controllers/comment.destroy'), $response->json('message'));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_should_not_allow_deleting_comments_from_other_users()
    {
        $comment = Comment::factory()->create();
        $response = $this->delete($this->endpoint . $comment->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
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
