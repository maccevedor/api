<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\CompanyService;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\SubscriptionRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Companies",
 *     description="API Endpoints for managing companies and their subscriptions"
 * )
 */
class CompanyController extends BaseController
{
    public function __construct(
        private readonly CompanyService $companyService
    ) {}

    /**
     * @OA\Get(
     *     path="/companies",
     *     summary="Get list of companies",
     *     tags={"Companies"},
     *     @OA\Response(
     *         response=200,
     *         description="List of companies retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Acme Corp"),
     *                     @OA\Property(property="email", type="string", format="email", example="contact@acme.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Business St"),
     *                     @OA\Property(
     *                         property="active_subscription",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="plan_id", type="integer", example=1),
     *                         @OA\Property(property="start_date", type="string", format="date-time"),
     *                         @OA\Property(property="end_date", type="string", format="date-time"),
     *                         @OA\Property(property="status", type="string", example="active")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Companies retrieved successfully")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $companies = $this->companyService->findAllCompanies();
        return $this->sendResponse($companies, 'Companies retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/companies",
     *     summary="Create a new company",
     *     tags={"Companies"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "address"},
     *             @OA\Property(property="name", type="string", example="Acme Corp"),
     *             @OA\Property(property="email", type="string", format="email", example="contact@acme.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Business St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Company created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Acme Corp"),
     *                 @OA\Property(property="email", type="string", format="email", example="contact@acme.com"),
     *                 @OA\Property(property="phone", type="string", example="+1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Business St")
     *             ),
     *             @OA\Property(property="message", type="string", example="Company created successfully")
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
    public function store(CompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->createCompany($request->validated());
        return $this->sendResponse($company, 'Company created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/companies/{id}",
     *     summary="Get company information",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Acme Corp"),
     *                 @OA\Property(property="email", type="string", format="email", example="contact@acme.com"),
     *                 @OA\Property(property="phone", type="string", example="+1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Business St"),
     *                 @OA\Property(
     *                     property="active_subscription",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="plan_id", type="integer", example=1),
     *                     @OA\Property(property="start_date", type="string", format="date-time"),
     *                     @OA\Property(property="end_date", type="string", format="date-time"),
     *                     @OA\Property(property="status", type="string", example="active")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Company retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Company not found")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->findCompanyById($id);
        if (!$company) {
            return $this->sendError('Company not found');
        }
        return $this->sendResponse($company, 'Company retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/companies/{id}",
     *     summary="Update company information",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "address"},
     *             @OA\Property(property="name", type="string", example="Updated Corp"),
     *             @OA\Property(property="email", type="string", format="email", example="updated@acme.com"),
     *             @OA\Property(property="phone", type="string", example="+1987654321"),
     *             @OA\Property(property="address", type="string", example="456 New Business St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Corp"),
     *                 @OA\Property(property="email", type="string", format="email", example="updated@acme.com"),
     *                 @OA\Property(property="phone", type="string", example="+1987654321"),
     *                 @OA\Property(property="address", type="string", example="456 New Business St")
     *             ),
     *             @OA\Property(property="message", type="string", example="Company updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Company not found")
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
    public function update(CompanyRequest $request, int $id): JsonResponse
    {
        $company = $this->companyService->updateCompany($request->validated());
        return $this->sendResponse($company, 'Company updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/companies/{id}",
     *     summary="Delete a company",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Company deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Company not found")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->companyService->deleteCompany($id);
        return $this->sendResponse(null, 'Company deleted successfully');
    }

    /**
     * @OA\Post(
     *     path="/companies/{id}/subscribe",
     *     summary="Subscribe a company to a plan",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"plan_id"},
     *             @OA\Property(property="plan_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company subscribed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="company_id", type="integer", example=1),
     *                 @OA\Property(property="plan_id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date-time"),
     *                 @OA\Property(property="end_date", type="string", format="date-time"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             ),
     *             @OA\Property(property="message", type="string", example="Company subscribed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company or plan not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Company or plan not found")
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
     *                 @OA\Property(property="plan_id", type="array", @OA\Items(type="string", example="The plan_id field is required"))
     *             )
     *         )
     *     )
     * )
     */
    public function subscribe(SubscriptionRequest $request, int $id): JsonResponse
    {
        $subscription = $this->companyService->subscribeToPlan($id, $request->validated()['plan_id']);
        return $this->sendResponse($subscription, 'Company subscribed successfully');
    }

    /**
     * @OA\Post(
     *     path="/companies/{id}/cancel-subscription",
     *     summary="Cancel a company's subscription",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subscription cancelled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Subscription cancelled successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found or no active subscription",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Company not found or no active subscription")
     *         )
     *     )
     * )
     */
    public function cancelSubscription(int $id): JsonResponse
    {
        $this->companyService->cancelSubscription($id);
        return $this->sendResponse(null, 'Subscription cancelled successfully');
    }

    /**
     * @OA\Get(
     *     path="/companies/{id}/subscriptions",
     *     summary="Get all subscriptions for a company",
     *     tags={"Companies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of subscriptions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="plan_id", type="integer", example=1),
     *                     @OA\Property(property="start_date", type="string", format="date-time"),
     *                     @OA\Property(property="end_date", type="string", format="date-time"),
     *                     @OA\Property(property="status", type="string", example="active")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Subscriptions retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Company not found")
     *         )
     *     )
     * )
     */
    public function subscriptions(int $id): JsonResponse
    {
        // ... existing code to return all subscriptions for a company ...
    }
}
