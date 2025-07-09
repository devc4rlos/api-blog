<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\CommentServiceInterface;
use App\Dto\Filter\FiltersRequestDto;
use App\Dto\Input\Comment\CreateCommentInputDto;
use App\Dto\Input\Comment\UpdateCommentInputDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Requests\V1\Comment\StandardCommentStoreRequest;
use App\Http\Requests\V1\Comment\StandardCommentUpdateRequest;
use App\Http\Resources\V1\StandardCommentResource;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StandardCommentController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    private CommentServiceInterface $service;

    public function __construct(CommentServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Comment::class);

        $user = $request->user();
        $comments = $this->service->allFromUser($user->id, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/comment.index'))
            ->setResultResource(StandardCommentResource::collection($comments))
            ->setPaginator(new PaginatorLengthAwarePaginator($comments, request()->except('page')))
            ->response();
    }

    public function store(StandardCommentStoreRequest $request)
    {
        $this->authorize('create', Comment::class);

        $user = $request->user();
        $commentDto = new CreateCommentInputDto(
            body: $request->input('body'),
            userId: $user->id,
            postId: $request->input('post_id'),
        );

        $comment = $this->service->create($commentDto);

        return ResponseApi::setMessage(__('controllers/comment.store'))
            ->setCode(201)
            ->setResultResource(StandardCommentResource::make($comment))
            ->response();
    }

    public function show(Comment $comment, Request $request)
    {
        $this->authorize('view', $comment);

        $comment = $this->service->findById($comment->id, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/comment.show'))
            ->setResultResource(StandardCommentResource::make($comment))
            ->response();
    }

    public function update(StandardCommentUpdateRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $commentDto = new UpdateCommentInputDto($request->validated());

        $this->service->update($comment, $commentDto);

        return ResponseApi::setMessage(__('controllers/comment.update'))
            ->setResultResource(StandardCommentResource::make($comment))
            ->response();
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $this->service->delete($comment);

        return ResponseApi::setMessage(__('controllers/comment.destroy'))
            ->setResultResource(StandardCommentResource::make($comment))
            ->response();
    }

    public static function middleware(): array
    {
        return [
            new Middleware(
                'validate.pagination:' . Comment::where('user_id', auth()->id() ?? 0)->count(),
                only: ['index']
            )
        ];
    }
}
