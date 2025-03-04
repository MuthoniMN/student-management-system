<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ResultRepositoryInterface;
use App\Services\PDFService;

class PDFServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PDFService::class, function($app) {
            return new PDFService(
                $app->make(ResultRepositoryInterface::class)
            );
        });
    }
}
