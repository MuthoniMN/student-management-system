<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Repositories\ExamRepository;
use App\Services\ExamService;

class ExamServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);

        $this->app->bind(ExamService::class, function($app){
            return new ExamService(
                $app->make(ExamRepositoryInterface::class),
                $app->make(SubjectRepositoryInterface::class),
                $app->make(SemesterRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class),
                $app->make(StudentRepositoryInterface::class),
                $app->make(ResultRepositoryInterface::class)
            );
        });
    }
}
