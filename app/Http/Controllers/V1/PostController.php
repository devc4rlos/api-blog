<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\PostServiceInterface;
use App\Dto\Filter\FiltersRequestDto;
use App\Dto\Input\Post\CreatePostInputDto;
use App\Dto\Input\Post\UpdatePostInputDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Requests\V1\Post\PostStoreRequest;
use App\Http\Requests\V1\Post\PostUpdateRequest;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller implements HasMiddleware
{
    private PostServiceInterface $service;

    public function __construct(PostServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $posts = $this->service->all(new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/post.index'))
            ->setResultResource(PostResource::collection($posts))
            ->setPaginator(new PaginatorLengthAwarePaginator($posts, request()->except('page')))
            ->response();
    }

    public function store(PostStoreRequest $request)
    {
        $postDto = new CreatePostInputDto(
            title: $request->input('title'),
            description: $request->input('description'),
            slug: $request->input('slug'),
            body: $request->input('body'),
            image: $request->file('image'),
            status: $request->input('status'),
        );

        $post = $this->service->create($postDto);

        return ResponseApi::setMessage(__('controllers/post.store'))
            ->setCode(201)
            ->setResultResource(PostResource::make($post))
            ->response();
    }

    public function show(Post $post, Request $request)
    {
        $post = $this->service->findById($post->id, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/post.show'))
            ->setResultResource(PostResource::make($post))
            ->response();
    }

    public function update(Post $post, PostUpdateRequest $request)
    {
        $postDto = new UpdatePostInputDto($request->validated());

        $this->service->update($post, $postDto);

        return ResponseApi::setMessage(__('controllers/post.update'))
            ->setResultResource(PostResource::make($post))
            ->response();
    }

    public function destroy(Post $post)
    {
        $this->service->delete($post);

        return ResponseApi::setMessage(__('controllers/post.destroy'))
            ->setResultResource(PostResource::make($post))
            ->response();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('validate.pagination:' . Post::class, only: ['index'])
        ];
    }
}
