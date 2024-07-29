<?php
use App\Http\Controllers\User\UserAuthController;

Route::middleware('guest:user-api')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});


Route::middleware('auth:user-api')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout']);
});
