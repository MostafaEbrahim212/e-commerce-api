<?php
use App\Http\Controllers\User\UserAuthController;

Route::middleware('guest:user-api')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login'])->middleware('check.status');
    Route::post('/register', [UserAuthController::class, 'register']);
});


Route::middleware(['auth:user-api', 'check.status'])->group(function () {
    Route::get('/user', [UserAuthController::class, 'user']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
});
