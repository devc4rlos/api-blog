<?php

use App\Facades\ResponseApi;
use App\Http\Controllers\V1\Auth\AuthenticateController;
use App\Http\Controllers\V1\UserController;

Route::get('/', function () {
    return ResponseApi::setMessage('Welcome to API')
        ->setCode(200)
        ->setResult(['status' => true])
        ->response();
});

Route::post('/login', [AuthenticateController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::post('logout', [AuthenticateController::class, 'logout']);
});
