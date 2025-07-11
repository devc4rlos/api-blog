<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\PostServiceInterface;
use App\Dto\Filter\FiltersRequestDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Resources\V1\CommentResource;
use App\Http\Resources\V1\StandardPostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StandardPostController extends Controller implements HasMiddleware
{
    private PostServiceInterface $service;

    public function __construct(PostServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $posts = $this->service->allPublished(new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/post.index'))
            ->setResultResource(StandardPostResource::collection($posts))
            ->setPaginator(new PaginatorLengthAwarePaginator($posts, request()->except('page')))
            ->response();
    }

    public function show(Post $post, Request $request)
    {
        $post = $this->service->findPublishedById($post->id, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/post.show'))
            ->setResultResource(StandardPostResource::make($post))
            ->response();
    }

    public function comments(Request $request, Post $post)
    {
        $comments = $this->service->allCommentsFromPublicPost($post, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/post.comments'))
            ->setResultResource(CommentResource::collection($comments))
            ->setPaginator(new PaginatorLengthAwarePaginator($comments, request()->except('page')))
            ->response();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('validate.pagination:' . Post::published()->count(), only: ['index'])
        ];
    }
}
