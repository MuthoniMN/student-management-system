<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SubjectService;
use App\Interfaces\SubjectRepositoryInterface;
use App\Interfaces\ExamRepositoryInterface;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Repositories\SubjectRepository;

class SubjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);

        $this->app->bind(SubjectService::class, function ($app){
            return new SubjectService(
                $app->make(SubjectRepositoryInterface::class),
                $app->make(ExamRepositoryInterface::class),
                $app->make(SemesterRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class),
            );
        });
    }

}
