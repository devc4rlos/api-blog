<?php

namespace Tests\Feature\Repositories\Comment;

use App\Dto\Filter\FiltersDto;
use App\Dto\Persistence\Comment\CreateCommentPersistenceDto;
use App\Dto\Persistence\Comment\UpdateCommentPersistenceDto;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Comment\EloquentCommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EloquentCommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('s3');
    }

    public function test_should_retrieved_all_comments()
    {
        $createdComments = Comment::factory()->count(5)->create();
        $repository = new EloquentCommentRepository();

        $retrievedComments = $repository->all(new FiltersDto());

        $createdIds = $createdComments->pluck('id')->sort()->values();
        $retrievedIds = $retrievedComments->pluck('id')->sort()->values();

        $this->assertCount(5, $retrievedComments);
        $this->assertEquals($createdIds, $retrievedIds);
    }

    public function test_should_retrieved_all_comments_from_user()
    {
        Comment::factory()->count(10)->create();

        $user = User::factory()->create();
        $createdComments = Comment::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);
        $repository = new EloquentCommentRepository();

        $retrievedComments = $repository->allFromUser($user->id, new FiltersDto());

        $createdIds = $createdComments->pluck('id')->sort()->values();
        $retrievedIds = $retrievedComments->pluck('id')->sort()->values();

        $this->assertCount(5, $retrievedComments);
        $this->assertEquals($createdIds, $retrievedIds);
    }

    public function test_should_find_comment_by_id()
    {
        $createdComment = Comment::factory()->create();
        $repository = new EloquentCommentRepository();

        $post = $repository->findById($createdComment->id, new FiltersDto());

        $this->assertEquals($createdComment->id, $post->id);
    }

    public function test_should_create_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $dto = new CreateCommentPersistenceDto(
            body: fake()->paragraph(4),
            userId: $user->id,
            postId: $post->id,
        );

        $repository = new EloquentCommentRepository();
        $comment = $repository->create($dto);

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'body' => $comment->body]);
    }

    public function test_should_update_comment()
    {
        $body = 'comment body';
        $createdComment = Comment::factory()->create();
        $dto = new UpdateCommentPersistenceDto(['body' => $body]);
        $repository = new EloquentCommentRepository();

        $repository->update($createdComment, $dto);

        $this->assertDatabaseHas('comments', ['id' => $createdComment->id, 'body' => $body]);
    }

    public function test_should_delete_comment()
    {
        $createdComment = Comment::factory()->create();
        $repository = new EloquentCommentRepository();

        $repository->delete($createdComment);
        $this->assertDatabaseMissing('comments', ['id' => $createdComment->id]);
    }
}
