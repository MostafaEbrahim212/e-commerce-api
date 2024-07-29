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
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->when(AdminAuthController::class)
            ->needs(AuthenticatableRepositoryInterface::class)
            ->give(function ($app) {
                return new AuthenticatableRepository(new Admin());
            });

        $this->app->when(UserAuthController::class)
            ->needs(AuthenticatableRepositoryInterface::class)
            ->give(function ($app) {
                return new AuthenticatableRepository(new User());
            });
        $this->app->when(CategoryController::class)
            ->needs(CrudRepositoryInterface::class)
            ->give(function ($app) {
                return new CrudRepository(new Category());
            });
        $this->app->when(ProductController::class)
            ->needs(CrudRepositoryInterface::class)
            ->give(function ($app) {
                return new CrudRepository(new Product());
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
