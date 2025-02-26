<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\GradeRepositoryInterface;
use App\Repositories\GradeRepository;
use App\Services\GradeService;

class GradeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(GradeService::class, function($app){
            return new GradeService(
                $app->make(GradeRepositoryInterface::class)
            );
        });
    }
}
