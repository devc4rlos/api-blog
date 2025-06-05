<?php

Route::get('/', function () {
    return response()->json(['status' => true]);
});
