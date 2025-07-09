<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Filter\FiltersRequestDto;
use App\Dto\Input\User\CreateUserInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Exceptions\BusinessRuleException;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Pagination\PaginatorLengthAwarePaginator;
use App\Http\Requests\V1\User\UserStoreRequest;
use App\Http\Requests\V1\User\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userService->all(new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/user.index'))
            ->setResultResource(UserResource::collection($users))
            ->setPaginator(new PaginatorLengthAwarePaginator($users, request()->except('page')))
            ->response();
    }

    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        $userDTO = new CreateUserInputDto($request->name, $request->email, $request->password, $request->get('is_admin', false));

        $user = $this->userService->create($userDTO);

        return ResponseApi::setMessage(__('controllers/user.store'))
            ->setCode(201)
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public function show(User $user, Request $request)
    {
        $this->authorize('view', $user);

        $user = $this->userService->findById($user->id, new FiltersRequestDto($request));

        return ResponseApi::setMessage(__('controllers/user.show'))
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        $this->authorize('update', $user);

        $userDTO = new UpdateUserInputDto($request->validated());

        $this->userService->update($user, $userDTO);

        return ResponseApi::setMessage(__('controllers/user.update'))
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            $this->userService->delete($user);
        } catch (BusinessRuleException $e) {
            return ResponseApi::setMessage($e->getMessage())
                ->setCode(403)
                ->response();
        }

        return ResponseApi::setMessage(__('controllers/user.destroy'))
            ->setResultResource(UserResource::make($user))
            ->response();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('validate.pagination:' . User::count(), only: ['index'])
        ];
    }
}
