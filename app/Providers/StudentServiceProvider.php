<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\ParentRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Services\StudentService;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\YearRepositoryInterface;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);

        $this->app->bind(StudentService::class, function ($app) {
            return new StudentService(
                $app->make(StudentRepositoryInterface::class),
                $app->make(ResultRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class),
                $app->make(ParentRepositoryInterface::class),
                $app->make(SemesterRepositoryInterface::class),
                $app->make(YearRepositoryInterface::class)
            );
        });
    }
}
