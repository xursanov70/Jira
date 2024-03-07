<?php

namespace App\Providers;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Interfaces\SendMyTaskInterface;
use App\Http\Interfaces\SendTaskInterface;
use App\Http\Interfaces\TaskInterface;
use App\Http\Repositories\RegisterRepository;
use App\Http\Repositories\SendMyTaskRepository;
use App\Http\Repositories\SendTaskRepository;
use App\Http\Repositories\TaskRepository;
use App\Rules\EndsWithCom;
use App\Rules\NoSpaces;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RegisterInterface::class, RegisterRepository::class);
        $this->app->singleton(SendTaskInterface::class, SendTaskRepository::class);
        $this->app->singleton(SendMyTaskInterface::class, SendMyTaskRepository::class);
        $this->app->singleton(TaskInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
