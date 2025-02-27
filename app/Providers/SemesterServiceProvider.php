<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\SemesterRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Interfaces\YearRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Repositories\SemesterRepository;
use App\Services\SemesterService;

class SemesterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SemesterRepositoryInterface::class, SemesterRepository::class);

        $this->app->bind(SemesterService::class, function($app){
            return new SemesterService(
                $app->make(SemesterRepositoryInterface::class),
                $app->make(YearRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class),
                $app->make(ResultRepositoryInterface::class),
            );
        });
    }
}
