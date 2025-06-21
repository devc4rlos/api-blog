<?php

namespace App\Http\Controllers\V1\Auth;

use App\Contracts\Services\AuthenticateServiceInterface;
use App\Dto\Auth\AuthCredentialDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;

class AuthenticateController extends Controller
{
    private AuthenticateServiceInterface $service;

    public function __construct(AuthenticateServiceInterface $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        $credentialsDTO = new AuthCredentialDto($request->email, $request->password);

        $token = $this->service->authenticate($credentialsDTO);

        return ResponseApi::setMessage(__('controllers/authenticate.login'))
            ->setResult([
                'email' => $credentialsDTO->email(),
                'token' => $token,
            ])
            ->response();
    }

    public function logout()
    {
        $user = auth()->user();
        $token = $user->currentAccessToken();

        $this->service->logout($user, $token);

        return ResponseApi::setMessage(__('controllers/authenticate.logout'))
            ->response();
    }
}
