<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\PlanDTO;
use App\Application\Interfaces\PlanServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlanController
{
    public function __construct(
        private PlanServiceInterface $planService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'monthly_price' => 'required|numeric|min:0',
            'user_limit' => 'required|integer|min:1',
            'features' => 'array',
            'features.*.name' => 'required|string|max:255',
            'features.*.description' => 'required|string',
        ]);

        $planDTO = PlanDTO::fromArray($validated);
        $plan = $this->planService->createPlan($planDTO);

        return response()->json([
            'data' => $plan->toArray()
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $plan = $this->planService->findPlanById($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        return response()->json([
            'data' => $plan->toArray()
        ]);
    }

    public function index(): JsonResponse
    {
        $plans = $this->planService->findAllPlans();

        return response()->json([
            'data' => array_map(fn($plan) => $plan->toArray(), $plans)
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $plan = $this->planService->findPlanById($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'monthly_price' => 'sometimes|numeric|min:0',
            'user_limit' => 'sometimes|integer|min:1',
        ]);

        $planDTO = PlanDTO::fromArray(array_merge(['id' => $id], $validated));
        $updatedPlan = $this->planService->updatePlan($planDTO);

        return response()->json([
            'data' => $updatedPlan->toArray()
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $plan = $this->planService->findPlanById($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $this->planService->deletePlan($id);

        return response()->json(null, 204);
    }
}
