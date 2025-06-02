<?php

namespace App\Application\Interfaces;

use App\Application\DTOs\PlanDTO;

interface PlanServiceInterface
{
    public function createPlan(PlanDTO $planDTO): PlanDTO;
    public function findPlanById(int $id): ?PlanDTO;
    public function findAllPlans(): array;
    public function updatePlan(PlanDTO $planDTO): PlanDTO;
    public function deletePlan(int $id): void;
}
