<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ParentRepositoryInterface;
use App\Repositories\ParentRepository;

class ParentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ParentServiceProvider::class, ParentRepository::class);
    }
}
