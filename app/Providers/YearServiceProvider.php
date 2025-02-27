<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\YearRepositoryInterface;
use App\Interfaces\ResultRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Repositories\YearRepository;
use App\Services\YearService;

class YearServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(YearRepositoryInterface::class, YearRepository::class);

        $this->app->bind(YearService::class, function($app){
            return new YearService(
                $app->make(YearRepositoryInterface::class),
                $app->make(ResultRepositoryInterface::class),
                $app->make(GradeRepositoryInterface::class)
            );
        });
    }

}
