<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\CompanyDTO;
use App\Application\Interfaces\CompanyServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController
{
    public function __construct(
        private CompanyServiceInterface $companyService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
        ]);

        $companyDTO = CompanyDTO::fromArray($validated);
        $company = $this->companyService->createCompany($companyDTO);

        return response()->json([
            'data' => $company->toArray()
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->findCompanyById($id);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND'
                ]
            ], 404);
        }

        return response()->json([
            'data' => $company->toArray()
        ]);
    }

    public function index(): JsonResponse
    {
        $companies = $this->companyService->findAllCompanies();

        return response()->json([
            'data' => array_map(
                fn(CompanyDTO $company) => $company->toArray(),
                $companies
            )
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:companies,email,' . $id,
        ]);

        $company = $this->companyService->findCompanyById($id);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND'
                ]
            ], 404);
        }

        $companyDTO = CompanyDTO::fromArray(array_merge(
            $company->toArray(),
            $validated
        ));

        $updatedCompany = $this->companyService->updateCompany($companyDTO);

        return response()->json([
            'data' => $updatedCompany->toArray()
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $company = $this->companyService->findCompanyById($id);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND'
                ]
            ], 404);
        }

        $this->companyService->deleteCompany($id);

        return response()->json(['message' => 'Company deleted successfully'],204);

    }

    public function subscribeToPlan(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $company = $this->companyService->findCompanyById($id);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND'
                ]
            ], 404);
        }

        $updatedCompany = $this->companyService->subscribeToPlan($id, $validated['plan_id']);

        return response()->json([
            'data' => $updatedCompany->toArray()
        ]);
    }

    public function cancelSubscription(int $id): JsonResponse
    {
        $company = $this->companyService->findCompanyById($id);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND'
                ]
            ], 404);
        }

        $updatedCompany = $this->companyService->cancelSubscription($id);

        return response()->json([
            'data' => $updatedCompany->toArray()
        ]);
    }
}
