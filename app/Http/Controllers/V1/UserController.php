<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\UserServiceInterface;
use App\DTO\Filter\FiltersRequestDTO;
use App\DTO\User\CreateUserInputDTO;
use App\DTO\User\UpdateUserInputDTO;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Requests\V1\User\UserStoreRequest;
use App\Http\Requests\V1\User\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->all(new FiltersRequestDTO($request));

        return ResponseApi::setMessage(__('controllers/user.index'))
            ->setResultResource(UserResource::collection($users))
            ->setPaginator(new PaginatorLengthAwarePaginator($users, request()->except('page')))
            ->response();
    }

    public function store(UserStoreRequest $request)
    {
        $userDTO = new CreateUserInputDTO($request->name, $request->email, $request->password);

        $user = $this->userService->create($userDTO);

        return ResponseApi::setMessage(__('controllers/user.store'))
            ->setCode(201)
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public function show(User $user, Request $request)
    {
        $user = $this->userService->findById($user->id, new FiltersRequestDTO($request));

        return ResponseApi::setMessage(__('controllers/user.show'))
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $userDTO = new UpdateUserInputDTO($request->validated());

        $this->userService->update($user, $userDTO);

        return ResponseApi::setMessage(__('controllers/user.update'))
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return ResponseApi::setMessage(__('controllers/user.destroy'))
            ->setResultResource(UserResource::make($user))
            ->response();
    }
}
