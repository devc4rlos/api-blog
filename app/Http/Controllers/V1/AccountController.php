<?php

namespace App\Http\Controllers\V1;

use App\Dto\Filter\FiltersRequestDto;
use App\Dto\Input\User\UpdateStandardUserInputDto;
use App\Dto\Input\User\UpdateUserInputDto;
use App\Exceptions\BusinessRuleException;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Account\AccountUpdateRequest;
use App\Http\Resources\V1\StandardUserResource;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use AuthorizesRequests;

    private UserService $userService;
    private User $user;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->user = auth()->user();
    }

    public function show(Request $request)
    {
        $user = $this->userService->findById($this->user->id, new FiltersRequestDto($request));

        $resource = $user->isAdmin() ? UserResource::make($user) : StandardUserResource::make($user);

        return ResponseApi::setMessage(__('controllers/user.show'))
            ->setResultResource($resource)
            ->response();
    }

    public function update(AccountUpdateRequest $request)
    {
        $userEntity = new User($request->validated());

        $dto = $this->user->isAdmin() ? new UpdateUserInputDto($userEntity->toArray()) : new UpdateStandardUserInputDto($userEntity->toArray());

        $this->userService->update($this->user, $dto);

        $resource = $this->user->isAdmin() ? UserResource::make($this->user) : StandardUserResource::make($this->user);

        return ResponseApi::setMessage(__('controllers/user.update'))
            ->setResultResource($resource)
            ->response();
    }

    public function destroy()
    {
        try {
            $this->userService->delete($this->user);
        } catch (BusinessRuleException $e) {
            return ResponseApi::setMessage($e->getMessage())
                ->setCode(403)
                ->response();
        }

        $resource = $this->user->isAdmin() ? UserResource::make($this->user) : StandardUserResource::make($this->user);

        return ResponseApi::setMessage(__('controllers/user.destroy'))
            ->setResultResource($resource)
            ->response();
    }
}
