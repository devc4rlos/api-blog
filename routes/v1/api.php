<?php

use App\Facades\ResponseApi;
use App\Http\Controllers\V1\AccountController;
use App\Http\Controllers\V1\Auth\AuthenticateController;
use App\Http\Controllers\V1\PasswordResetController;
use App\Http\Controllers\V1\RegisterStandardUserController;
use App\Http\Controllers\V1\UserController;

Route::get('/', function () {
    return ResponseApi::setMessage('Welcome to API')
        ->setCode(200)
        ->setResult(['status' => true])
        ->response();
});

Route::post('/login', [AuthenticateController::class, 'login']);
Route::post('/register', RegisterStandardUserController::class);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/account', [AccountController::class, 'show']);
    Route::patch('/account', [AccountController::class, 'update']);
    Route::delete('/account', [AccountController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'auth.admin'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::post('logout', [AuthenticateController::class, 'logout']);
});
