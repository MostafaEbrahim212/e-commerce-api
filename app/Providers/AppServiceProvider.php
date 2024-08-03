<?php

namespace App\Providers;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\UserAuthController;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Repositories\AuthenticatableRepository;
use App\Repositories\CrudRepository;
use App\Repositories\Interfaces\AuthenticatableRepositoryInterface;
use App\Repositories\Interfaces\CrudRepositoryInterface;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind CrudRepositoryInterface to CrudRepository with Category
        $this->app->when(CategoryController::class)
            ->needs(CrudRepositoryInterface::class)
            ->give(function ($app) {
                return new CrudRepository(new Category());
            });

        // Bind CrudRepositoryInterface to CrudRepository with Product
        $this->app->when(ProductController::class)
            ->needs(CrudRepositoryInterface::class)
            ->give(function ($app) {
                return new CrudRepository(new Product());
            });

        // Bind AuthenticatableRepositoryInterface to AuthenticatableRepository with Admin
        $this->app->when(AdminAuthController::class)
            ->needs(AuthenticatableRepositoryInterface::class)
            ->give(function ($app) {
                return new AuthenticatableRepository(new Admin());
            });

        // Bind AuthenticatableRepositoryInterface to AuthenticatableRepository with User
        $this->app->when(UserAuthController::class)
            ->needs(AuthenticatableRepositoryInterface::class)
            ->give(function ($app) {
                return new AuthenticatableRepository(new User());
            });

        // Optionally bind services if they are used
        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CrudRepositoryInterface::class));
        });

        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(CrudRepositoryInterface::class));
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
