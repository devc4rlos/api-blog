<?php

namespace Tests\Unit\Services;

use App\Dto\Filter\FiltersDto;
use App\Dto\Input\Comment\CreateCommentInputDto;
use App\Dto\Input\Comment\UpdateCommentInputDto;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Services\CommentService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    private MockInterface&CommentRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CommentRepositoryInterface::class);
    }

    public function test_should_return_all_comments()
    {
        $lengthAwarePaginator = Mockery::mock(LengthAwarePaginator::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('all')
            ->andReturn($lengthAwarePaginator)
            ->once();

        $service = new CommentService($this->repository);
        $service->all($filtersDTO);
    }

    public function test_should_return_comment_by_id()
    {
        $comment = Mockery::mock(Comment::class);
        $filtersDTO = new FiltersDto();

        $this->repository->shouldReceive('findById')
            ->andReturn($comment)
            ->once();

        $service = new CommentService($this->repository);
        $service->findById(1, $filtersDTO);
    }

    public function test_should_create_comment(): void
    {
        $dto = new CreateCommentInputDto(
            body: fake()->paragraph(5),
            userId: 1,
            postId: 1,
        );

        $this->repository->shouldReceive('create')
            ->andReturn()
            ->once();

        $service = new CommentService($this->repository);
        $service->create($dto);
    }

    public function test_should_update_comment(): void
    {
        $comment = Mockery::mock(Comment::class);
        $dto = new UpdateCommentInputDto(['body' => 'Updated body']);

        $this->repository->shouldReceive('update')
            ->andReturn()
            ->once();

        $service = new CommentService($this->repository);
        $service->update($comment, $dto);
    }

    public function test_should_delete_comment()
    {
        $comment = Mockery::mock(Comment::class);
        $this->repository->shouldReceive('delete')
            ->andReturn(true)
            ->once();

        $service = new CommentService($this->repository);

        $service->delete($comment);
    }
}
