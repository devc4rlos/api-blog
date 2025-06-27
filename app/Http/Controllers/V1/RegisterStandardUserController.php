<?php

namespace App\Http\Controllers\V1;

use App\Contracts\Services\UserServiceInterface;
use App\Dto\Input\User\CreateUserInputDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserStoreRequest;
use App\Http\Resources\V1\UserResource;

class RegisterStandardUserController extends Controller
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(UserStoreRequest $request)
    {
        $dto = new CreateUserInputDto($request->name, $request->email, $request->password, isAdmin: false);
        $user = $this->userService->createStandardUser($dto);

        return ResponseApi::setMessage(__('controllers/user.store'))
            ->setCode(201)
            ->setResultResource(UserResource::make($user))
            ->response();
    }
}
