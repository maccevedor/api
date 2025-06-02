<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Plan;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Models\Plan as PlanModel;

class EloquentPlanRepository implements PlanRepositoryInterface
{
    public function findById(int $id): ?Plan
    {
        $planModel = PlanModel::with('features')->find($id);

        if (!$planModel) {
            return null;
        }

        return $this->toEntity($planModel);
    }

    public function findAll(): array
    {
        return PlanModel::with('features')
            ->get()
            ->map(fn($planModel) => $this->toEntity($planModel))
            ->toArray();
    }

    public function save(Plan $plan): void
    {
        $planModel = PlanModel::updateOrCreate(
            ['id' => $plan->getId()],
            [
                'name' => $plan->getName(),
                'monthly_price' => $plan->getMonthlyPrice()->getAmount(),
                'user_limit' => $plan->getUserLimit(),
            ]
        );

        // Save features
        $planModel->features()->delete();
        foreach ($plan->getFeatures() as $feature) {
            $planModel->features()->create([
                'name' => $feature->getName(),
                'description' => $feature->getDescription(),
            ]);
        }
    }

    public function delete(Plan $plan): void
    {
        if ($plan->getId()) {
            PlanModel::destroy($plan->getId());
        }
    }

    private function toEntity(PlanModel $model): Plan
    {
        $features = $model->features->map(
            fn($feature) => new \App\Domain\ValueObjects\Feature(
                $feature->name,
                $feature->description
            )
        )->toArray();

        return new Plan(
            $model->name,
            new \App\Domain\ValueObjects\Money($model->monthly_price),
            $model->user_limit,
            $features,
            $model->id
        );
    }
}
