<?php

use App\Facades\ResponseApi;
use App\Http\Controllers\V1\CommentController;
use App\Http\Controllers\V1\StandardCommentController;
use App\Http\Controllers\V1\StandardPostController;
use App\Http\Controllers\V1\AccountController;
use App\Http\Controllers\V1\Auth\AuthenticateController;
use App\Http\Controllers\V1\PasswordResetController;
use App\Http\Controllers\V1\PostController;
use App\Http\Controllers\V1\RegisterStandardUserController;
use App\Http\Controllers\V1\UserController;

Route::middleware(['throttle:auth'])->group(function () {
    Route::post('/login', [AuthenticateController::class, 'login']);
    Route::post('/register', RegisterStandardUserController::class);
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
});

Route::middleware(['throttle:api'])->group(function () {
    Route::get('/', function () {
        return ResponseApi::setMessage('Welcome to API')
            ->setCode(200)
            ->setResult(['status' => true])
            ->response();
    });

    Route::get('/posts', [StandardPostController::class, 'index']);
    Route::get('/posts/{post:slug}', [StandardPostController::class, 'show']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/account', [AccountController::class, 'show']);
        Route::patch('/account', [AccountController::class, 'update']);
        Route::delete('/account', [AccountController::class, 'destroy']);

        Route::apiResource('comments', StandardCommentController::class);
    });

    Route::middleware(['auth:sanctum', 'auth.admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::apiResource('posts', PostController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('comments', CommentController::class);
        Route::post('logout', [AuthenticateController::class, 'logout']);
    });
});
