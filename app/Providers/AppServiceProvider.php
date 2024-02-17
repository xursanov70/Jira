<?php

namespace App\Providers;

use App\Http\Interfaces\CategoryInterface;
use App\Http\Interfaces\CommentInterface;
use App\Http\Interfaces\RegisterInterface;
use App\Http\Interfaces\TaskInterface;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\CommentRepository;
use App\Http\Repositories\RegisterRepository;
use App\Http\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RegisterInterface::class, RegisterRepository::class);
        $this->app->singleton(CategoryInterface::class, CategoryRepository::class);
        $this->app->singleton(CommentInterface::class, CommentRepository::class);
        $this->app->singleton(TaskInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
