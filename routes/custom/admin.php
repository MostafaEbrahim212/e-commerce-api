<?php
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UsersController;

Route::middleware('guest:admin-api')->prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware('auth:admin-api')->prefix('admin')->group(function () {
    Route::get('/me', [AdminAuthController::class, 'me']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::get('/categories/{category}/products', [CategoryController::class, 'categorProducts']);

    Route::apiResource('users', UsersController::class);
    Route::post('/users/{user}/toggle-status', [UsersController::class, 'toggleStatus']);

    Route::get('/orders', [OrdersController::class, 'index']);
    Route::get('/orders/{order}', [OrdersController::class, 'show']);
    Route::post('/orders/{order}/accept', [OrdersController::class, 'accept']);
    Route::post('/orders/{order}/reject', [OrdersController::class, 'reject']);
});
Route::get('/products/images/{image}', [ProductController::class, 'image']);
Route::get('/categories/images/{image}', [CategoryController::class, 'image']);
