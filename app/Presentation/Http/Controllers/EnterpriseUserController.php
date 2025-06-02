<?php

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\EnterpriseUserDTO;
use App\Application\Interfaces\CompanyServiceInterface;
use App\Application\Interfaces\EnterpriseUserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EnterpriseUserController
{
    public function __construct(
        private EnterpriseUserServiceInterface $userService,
        private CompanyServiceInterface $companyService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $companyId = $request->input('company_id');

        if (!$companyId) {
            return response()->json([
                'error' => [
                    'message' => 'Company ID is required',
                    'code' => 'COMPANY_ID_REQUIRED'
                ]
            ], 400);
        }

        $company = $this->companyService->findCompanyById($companyId);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND',
                    'details' => [
                        'company_id' => $companyId
                    ]
                ]
            ], 404);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:enterprise_users,email',
                'password' => 'required|string|min:8',
                'company_id' => 'required|exists:companies,id',
            ]);

            $userDTO = EnterpriseUserDTO::fromArray($validated);
            $user = $this->userService->createUser($userDTO);

            return response()->json([
                'data' => $user->toArray()
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'USER_CREATION_FAILED'
                ]
            ], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'User not found',
                    'code' => 'USER_NOT_FOUND'
                ]
            ], 404);
        }

        return response()->json([
            'data' => $user->toArray()
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $company = $this->companyService->findCompanyById($validated['company_id']);

        if (!$company) {
            return response()->json([
                'error' => [
                    'message' => 'Company not found',
                    'code' => 'COMPANY_NOT_FOUND'
                ]
            ], 404);
        }

        $users = $this->userService->findUsersByCompany($validated['company_id']);

        return response()->json([
            'data' => array_map(
                fn(EnterpriseUserDTO $user) => $user->toArray(),
                $users
            )
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'User not found',
                    'code' => 'USER_NOT_FOUND'
                ]
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:enterprise_users,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        $userDTO = EnterpriseUserDTO::fromArray(array_merge(
            $user->toArray(),
            $validated
        ));

        $updatedUser = $this->userService->updateUser($userDTO);

        return response()->json([
            'data' => $updatedUser->toArray()
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'User not found',
                    'code' => 'USER_NOT_FOUND'
                ]
            ], 404);
        }

        $this->userService->deleteUser($id);

        return response()->json(null, 204);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->userService->authenticate(
            $validated['email'],
            $validated['password']
        );

        if (!$user) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid credentials',
                    'code' => 'INVALID_CREDENTIALS'
                ]
            ], 401);
        }

        return response()->json([
            'data' => $user->toArray()
        ]);
    }
}
