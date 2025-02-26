<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\StudentRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Interfaces\ResultRepositoryInterface;
use App\Repositories\ResultRepository;
use App\Interfaces\GradeRepositoryInterface;
use App\Repositories\GradeRepository;
use App\Interfaces\ParentRepositoryInterface;
use App\Repositories\ParentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the interfaces to their implementations
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(ResultRepositoryInterface::class, ResultRepository::class);
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(ParentRepositoryInterface::class, ParentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
