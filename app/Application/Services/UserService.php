<?php

namespace App\Application\Services;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function createUser(string $name, string $email, string $password): User
    {
        $user = new User(
            $name,
            new Email($email),
            new Password($password)
        );

        $this->userRepository->save($user);
        return $user;
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail(new Email($email));
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function updateUser(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->delete($user);
    }

    public function verifyUserEmail(User $user): void
    {
        $user->markEmailAsVerified();
        $this->userRepository->save($user);
    }
}
