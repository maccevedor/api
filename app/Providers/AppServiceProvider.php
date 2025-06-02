<?php

namespace App\Providers;

use App\Application\Interfaces\CompanyServiceInterface;
use App\Application\Interfaces\PlanServiceInterface;
use App\Application\Services\CompanyService;
use App\Application\Services\PlanService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlanServiceInterface::class, PlanService::class);
        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
