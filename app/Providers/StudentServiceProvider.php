<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\ParentRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Repositories\ParentRepository;
use App\Services\StudentService;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(ParentRepositoryInterface::class, ParentRepository::class);

        $this->app->bind(StudentService::class, function ($app) {
            return new StudentService(
                $app->make(StudentRepositoryInterface::class),
                $app->make(ResultRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class),
                $app->make(ParentRepositoryInterface::class)
            );
        });
    }
}
