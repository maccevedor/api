<?php

namespace App\Application\Services;

use App\Application\DTOs\PlanDTO;
use App\Application\Interfaces\PlanServiceInterface;
use App\Domain\Entities\Plan;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Domain\ValueObjects\Feature;
use App\Domain\ValueObjects\Money;

class PlanService implements PlanServiceInterface
{
    public function __construct(
        private PlanRepositoryInterface $planRepository
    ) {}

    public function createPlan(PlanDTO $planDTO): PlanDTO
    {
        $features = array_map(
            fn($feature) => new Feature($feature['name'], $feature['description']),
            $planDTO->features
        );

        $plan = new Plan(
            $planDTO->name,
            new Money($planDTO->monthlyPrice),
            $planDTO->userLimit,
            $features
        );

        $this->planRepository->save($plan);

        return PlanDTO::fromArray([
            'id' => $plan->getId(),
            'name' => $plan->getName(),
            'monthly_price' => $plan->getMonthlyPrice()->getAmount(),
            'user_limit' => $plan->getUserLimit(),
            'features' => array_map(
                fn($feature) => [
                    'name' => $feature->getName(),
                    'description' => $feature->getDescription(),
                ],
                $plan->getFeatures()
            ),
        ]);
    }

    public function findPlanById(int $id): ?PlanDTO
    {
        $plan = $this->planRepository->findById($id);

        if (!$plan) {
            return null;
        }

        return PlanDTO::fromArray([
            'id' => $plan->getId(),
            'name' => $plan->getName(),
            'monthly_price' => $plan->getMonthlyPrice()->getAmount(),
            'user_limit' => $plan->getUserLimit(),
            'features' => array_map(
                fn($feature) => [
                    'name' => $feature->getName(),
                    'description' => $feature->getDescription(),
                ],
                $plan->getFeatures()
            ),
        ]);
    }

    public function findAllPlans(): array
    {
        return array_map(
            fn(Plan $plan) => PlanDTO::fromArray([
                'id' => $plan->getId(),
                'name' => $plan->getName(),
                'monthly_price' => $plan->getMonthlyPrice()->getAmount(),
                'user_limit' => $plan->getUserLimit(),
                'features' => array_map(
                    fn($feature) => [
                        'name' => $feature->getName(),
                        'description' => $feature->getDescription(),
                    ],
                    $plan->getFeatures()
                ),
            ]),
            $this->planRepository->findAll()
        );
    }

    public function updatePlan(PlanDTO $planDTO): PlanDTO
    {
        $plan = $this->planRepository->findById($planDTO->id);

        if (!$plan) {
            throw new \InvalidArgumentException('Plan not found');
        }

        $features = array_map(
            fn($feature) => new Feature($feature['name'], $feature['description']),
            $planDTO->features
        );

        $updatedPlan = new Plan(
            $planDTO->name,
            new Money($planDTO->monthlyPrice),
            $planDTO->userLimit,
            $features,
            $planDTO->id
        );

        $this->planRepository->save($updatedPlan);

        return PlanDTO::fromArray([
            'id' => $updatedPlan->getId(),
            'name' => $updatedPlan->getName(),
            'monthly_price' => $updatedPlan->getMonthlyPrice()->getAmount(),
            'user_limit' => $updatedPlan->getUserLimit(),
            'features' => array_map(
                fn($feature) => [
                    'name' => $feature->getName(),
                    'description' => $feature->getDescription(),
                ],
                $updatedPlan->getFeatures()
            ),
        ]);
    }

    public function deletePlan(int $id): void
    {
        $plan = $this->planRepository->findById($id);

        if (!$plan) {
            throw new \InvalidArgumentException('Plan not found');
        }

        $this->planRepository->delete($plan);
    }

    public function addFeatureToPlan(Plan $plan, string $name, string $description): void
    {
        $plan->addFeature(new Feature($name, $description));
        $this->planRepository->save($plan);
    }

    public function removeFeatureFromPlan(Plan $plan, string $name, string $description): void
    {
        $plan->removeFeature(new Feature($name, $description));
        $this->planRepository->save($plan);
    }
}
