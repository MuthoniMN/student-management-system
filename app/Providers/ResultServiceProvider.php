<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\ExamRepositoryInterface;
use App\Repositories\ResultRepository;
use App\Interfaces\SubjectRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\YearRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Services\ResultService;

class ResultServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ResultRepositoryInterface::class, ResultRepository::class);

        $this->app->bind(ResultService::class, function($app){
            return new ResultService(
                $app->make(ExamRepositoryInterface::class),
                $app->make(SubjectRepositoryInterface::class),
                $app->make(SemesterRepositoryInterface::class),
                $app->make(YearRepositoryInterface::class),
                $app->make(StudentRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class),
                $app->make(ResultRepositoryInterface::class),
            );
        });
    }
}
