<?php

namespace App\Http\Controllers\V1;

use App\Dto\Input\PasswordReset\PasswordResetInputDto;
use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PasswordReset\ForgotPasswordRequest;
use App\Http\Requests\V1\PasswordReset\ResetPasswordRequest;
use App\Services\PasswordResetService;
use DomainException;

class PasswordResetController extends Controller
{
    private PasswordResetService $service;

    public function __construct(PasswordResetService $service)
    {
        $this->service = $service;
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->service->forgotPassword($request->email);

        return ResponseApi::setMessage(__('controllers/password-reset.forgot-password'))
            ->response();
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $dto = new PasswordResetInputDto($request->email, $request->code, $request->password);

        try {
            $this->service->resetPassword($dto);
        } catch (DomainException) {
            return ResponseApi::setMessage(__('controllers/password-reset.reset-password-failure'))
                ->setCode(422)
                ->response();
        }

        return ResponseApi::setMessage(__('controllers/password-reset.reset-password-success'))
            ->response();
    }
}
