<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\PlanService;
use App\Http\Requests\PlanRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Plans",
 *     description="API Endpoints for managing subscription plans"
 * )
 */
class PlanController extends BaseController
{
    public function __construct(
        private readonly PlanService $planService
    ) {}

    /**
     * @OA\Get(
     *     path="/plans",
     *     summary="Get list of plans",
     *     tags={"Plans"},
     *     @OA\Response(
     *         response=200,
     *         description="List of plans retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Basic Plan"),
     *                     @OA\Property(property="monthly_price", type="number", format="float", example=9.99),
     *                     @OA\Property(property="user_limit", type="integer", example=5),
     *                     @OA\Property(
     *                         property="features",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Feature 1"),
     *                             @OA\Property(property="description", type="string", example="Description of feature 1")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Plans retrieved successfully")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $plans = $this->planService->findAllPlans();
        return $this->sendResponse($plans, 'Plans retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/plans",
     *     summary="Create a new plan",
     *     tags={"Plans"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "monthly_price", "user_limit", "features"},
     *             @OA\Property(property="name", type="string", example="Basic Plan"),
     *             @OA\Property(property="monthly_price", type="number", format="float", example=9.99),
     *             @OA\Property(property="user_limit", type="integer", example=5),
     *             @OA\Property(
     *                 property="features",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="Feature 1"),
     *                     @OA\Property(property="description", type="string", example="Description of feature 1")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plan created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Basic Plan"),
     *                 @OA\Property(property="monthly_price", type="number", format="float", example=9.99),
     *                 @OA\Property(property="user_limit", type="integer", example=5),
     *                 @OA\Property(
     *                     property="features",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Feature 1"),
     *                         @OA\Property(property="description", type="string", example="Description of feature 1")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Plan created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required"))
     *             )
     *         )
     *     )
     * )
     */
    public function store(PlanRequest $request): JsonResponse
    {
        $plan = $this->planService->createPlan($request->validated());
        return $this->sendResponse($plan, 'Plan created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/plans/{id}",
     *     summary="Get plan information",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Plan ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Basic Plan"),
     *                 @OA\Property(property="monthly_price", type="number", format="float", example=9.99),
     *                 @OA\Property(property="user_limit", type="integer", example=5),
     *                 @OA\Property(
     *                     property="features",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Feature 1"),
     *                         @OA\Property(property="description", type="string", example="Description of feature 1")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Plan retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Plan not found")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $plan = $this->planService->findPlanById($id);
        if (!$plan) {
            return $this->sendError('Plan not found');
        }
        return $this->sendResponse($plan, 'Plan retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/plans/{id}",
     *     summary="Update plan information",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Plan ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "monthly_price", "user_limit", "features"},
     *             @OA\Property(property="name", type="string", example="Updated Plan"),
     *             @OA\Property(property="monthly_price", type="number", format="float", example=19.99),
     *             @OA\Property(property="user_limit", type="integer", example=10),
     *             @OA\Property(
     *                 property="features",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="Updated Feature"),
     *                     @OA\Property(property="description", type="string", example="Updated description")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Plan"),
     *                 @OA\Property(property="monthly_price", type="number", format="float", example=19.99),
     *                 @OA\Property(property="user_limit", type="integer", example=10),
     *                 @OA\Property(
     *                     property="features",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Updated Feature"),
     *                         @OA\Property(property="description", type="string", example="Updated description")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Plan updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Plan not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required"))
     *             )
     *         )
     *     )
     * )
     */
    public function update(PlanRequest $request, int $id): JsonResponse
    {
        $plan = $this->planService->updatePlan($request->validated());
        return $this->sendResponse($plan, 'Plan updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/plans/{id}",
     *     summary="Delete a plan",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Plan ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Plan deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plan not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Plan not found")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->planService->deletePlan($id);
        return $this->sendResponse(null, 'Plan deleted successfully');
    }
}
