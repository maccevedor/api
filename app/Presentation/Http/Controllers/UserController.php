<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\UserService;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = $this->userService->createUser(
            $validated['name'],
            $validated['email'],
            $validated['password']
        );

        return response()->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
            ]
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
                'email_verified_at' => $user->getEmailVerifiedAt(),
            ]
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
        ]);

        if (isset($validated['name'])) {
            $user = new User(
                $validated['name'],
                $user->getEmail(),
                $user->getPassword()
            );
        }

        if (isset($validated['email'])) {
            $user = new User(
                $user->getName(),
                new Email($validated['email']),
                $user->getPassword()
            );
        }

        $this->userService->updateUser($user);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
            ]
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userService->deleteUser($user);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
