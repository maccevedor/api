<?php

namespace App\Providers;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Infrastructure\Repositories\EloquentPlanRepository;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\ServiceProvider;
use App\Application\Interfaces\EnterpriseUserServiceInterface;
use App\Application\Services\EnterpriseUserService;
use App\Domain\Repositories\EnterpriseUserRepositoryInterface;
use App\Infrastructure\Repositories\EloquentEnterpriseUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, EloquentPlanRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);
        $this->app->bind(EnterpriseUserServiceInterface::class, EnterpriseUserService::class);
        $this->app->bind(EnterpriseUserRepositoryInterface::class, EloquentEnterpriseUserRepository::class);
    }
}
