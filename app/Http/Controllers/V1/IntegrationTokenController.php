<?php

namespace App\Http\Controllers\V1;

use App\Facades\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\IntegrationTokenResource;
use App\Services\IntegrationTokenService;
use DomainException;
use Illuminate\Http\Request;

class IntegrationTokenController extends Controller
{
    private IntegrationTokenService $service;

    public function __construct(IntegrationTokenService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $accessToken = $this->service->getIntegrationToken($user);

        return ResponseApi::setMessage('Integration Token details retrieved successfully.')
            ->setResultResource(IntegrationTokenResource::make($accessToken))
            ->response();
    }

    public function store(Request $request)
    {
        $user = $request->user();

        try {
            $newAccessToken = $this->service->createIntegrationToken($user);

            return ResponseApi::setMessage('Integration Token generated successfully. Please store it in a safe place, as it will not be shown again.')
                ->setResult([
                    'token' => $newAccessToken->plainTextToken,
                    'created_at' => $newAccessToken->accessToken->created_at,
                ])
                ->setCode(201)
                ->response();
        } catch (DomainException $e) {
            return ResponseApi::setMessage($e->getMessage())
                ->setCode(409)
                ->response();
        }
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        try {
            $this->service->revokeIntegrationToken($user);
        } catch (DomainException) {
        }

        return ResponseApi::setMessage('Integration Token revoked successfully.')
            ->setCode(200)
            ->response();
    }
}
