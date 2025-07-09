<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\CommentServiceInterface;
use App\Dto\Filter\FiltersRequestDto;
use App\Dto\Input\Comment\CreateCommentInputDto;
use App\Dto\Input\Comment\UpdateCommentInputDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Requests\V1\Comment\CommentStoreRequest;
use App\Http\Requests\V1\Comment\CommentUpdateRequest;
use App\Http\Resources\V1\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CommentController extends Controller implements HasMiddleware
{
    private CommentServiceInterface $service;

    public function __construct(CommentServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $comments = $this->service->all(new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/comment.index'))
            ->setResultResource(CommentResource::collection($comments))
            ->setPaginator(new PaginatorLengthAwarePaginator($comments, request()->except('page')))
            ->response();
    }

    public function store(CommentStoreRequest $request)
    {
        $commentDto = new CreateCommentInputDto(
            body: $request->input('body'),
            userId: $request->input('user_id'),
            postId: $request->input('post_id'),
        );

        $comment = $this->service->create($commentDto);

        return ResponseApi::setMessage(__('controllers/comment.store'))
            ->setCode(201)
            ->setResultResource(CommentResource::make($comment))
            ->response();
    }

    public function show(Comment $comment, Request $request)
    {
        $comment = $this->service->findById($comment->id, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/comment.show'))
            ->setResultResource(CommentResource::make($comment))
            ->response();
    }

    public function update(CommentUpdateRequest $request, Comment $comment)
    {
        $commentDto = new UpdateCommentInputDto($request->validated());

        $this->service->update($comment, $commentDto);

        return ResponseApi::setMessage(__('controllers/comment.update'))
            ->setResultResource(CommentResource::make($comment))
            ->response();
    }

    public function destroy(Comment $comment)
    {
        $this->service->delete($comment);

        return ResponseApi::setMessage(__('controllers/comment.destroy'))
            ->setResultResource(CommentResource::make($comment))
            ->response();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('validate.pagination:' . Comment::count(), only: ['index'])
        ];
    }
}
