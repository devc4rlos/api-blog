<?php

use App\Facades\ResponseApi;

Route::get('/', function () {
    return ResponseApi::setMessage('Welcome to API')
        ->setCode(200)
        ->setResult(['status' => true])
        ->response();
});
