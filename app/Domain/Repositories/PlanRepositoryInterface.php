<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Plan;

interface PlanRepositoryInterface
{
    public function findById(int $id): ?Plan;
    public function findAll(): array;
    public function save(Plan $plan): void;
    public function delete(Plan $plan): void;
}
