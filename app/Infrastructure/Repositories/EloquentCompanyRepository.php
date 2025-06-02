<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Company;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Models\Company as CompanyModel;
use App\Domain\ValueObjects\Email;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(
        private PlanRepositoryInterface $planRepository
    ) {}

    public function findById(int $id): ?Company
    {
        $model = CompanyModel::with('activeSubscription.plan')->find($id);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByEmail(Email $email): ?Company
    {
        $model = CompanyModel::with('activeSubscription.plan')
            ->where('email', $email->getValue())
            ->first();

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(): array
    {
        return CompanyModel::with('activeSubscription.plan')
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(Company $company): void
    {
        $model = $company->getId()
            ? CompanyModel::find($company->getId())
            : new CompanyModel();

        $model->name = $company->getName();
        $model->email = $company->getEmail()->getValue();
        $model->save();

        if ($subscription = $company->getActiveSubscription()) {
            $model->activeSubscription()->updateOrCreate(
                ['company_id' => $model->id],
                [
                    'plan_id' => $subscription->getPlan()->getId(),
                    'status' => $subscription->getStatus()->getValue(),
                    'start_date' => $subscription->getStartDate(),
                    'end_date' => $subscription->getEndDate(),
                ]
            );
        }
    }

    public function delete(Company $company): void
    {
        CompanyModel::destroy($company->getId());
    }

    private function toEntity(CompanyModel $model): Company
    {
        $company = new Company(
            $model->name,
            new Email($model->email),
            $model->id
        );

        if ($model->activeSubscription) {
            $company->subscribe($model->activeSubscription->plan->toEntity());
        }

        return $company;
    }
}
