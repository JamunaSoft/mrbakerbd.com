<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, EloquentRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
