<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\PostServiceInterface;
use App\Dto\Filter\FiltersRequestDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Resources\V1\StandardPostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class StandardPostController extends Controller
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
}
