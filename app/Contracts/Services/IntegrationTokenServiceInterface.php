<?php

namespace App\Contracts\Services;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

interface IntegrationTokenServiceInterface
{
    public function getIntegrationToken(User $user): ?PersonalAccessToken;
    public function createIntegrationToken(User $user): NewAccessToken;
    public function revokeIntegrationToken(User $user): bool;
}
