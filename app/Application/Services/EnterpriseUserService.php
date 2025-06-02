<?php

namespace App\Application\Services;

use App\Application\DTOs\EnterpriseUserDTO;
use App\Application\Interfaces\EnterpriseUserServiceInterface;
use App\Domain\Entities\Company;
use App\Domain\Entities\EnterpriseUser;
use App\Domain\Repositories\EnterpriseUserRepositoryInterface;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class EnterpriseUserService implements EnterpriseUserServiceInterface
{
    public function __construct(
        private EnterpriseUserRepositoryInterface $userRepository,
        private CompanyRepositoryInterface $companyRepository
    ) {}

    public function createUser(EnterpriseUserDTO $userDTO): EnterpriseUserDTO
    {
        $company = $this->companyRepository->findById($userDTO->companyId);

        if (!$company) {
            throw new \InvalidArgumentException('Company not found');
        }

        if (!$company->hasActiveSubscription()) {
            throw new \InvalidArgumentException('Company does not have an active subscription');
        }

        if (!$company->canAddMoreUsers()) {
            throw new \InvalidArgumentException('Company has reached its user limit');
        }

        $existingUser = $this->userRepository->findByEmail(new Email($userDTO->email));
        if ($existingUser) {
            throw new \InvalidArgumentException('Email is already in use');
        }

        $user = new EnterpriseUser(
            $userDTO->name,
            new Email($userDTO->email),
            new Password($userDTO->password),
            $company
        );

        $company->addUser($user);
        $this->userRepository->save($user);

        return EnterpriseUserDTO::fromEntity($user);
    }

    public function findUserById(int $id): ?EnterpriseUserDTO
    {
        $user = $this->userRepository->findById($id);
        return $user ? EnterpriseUserDTO::fromEntity($user) : null;
    }

    public function findUserByEmail(string $email): ?EnterpriseUserDTO
    {
        $user = $this->userRepository->findByEmail(new Email($email));
        return $user ? EnterpriseUserDTO::fromEntity($user) : null;
    }

    public function findUsersByCompany(int $companyId): array
    {
        $company = $this->companyRepository->findById($companyId);

        if (!$company) {
            throw new \InvalidArgumentException('Company not found');
        }

        $users = $this->userRepository->findByCompany($company);
        return array_map(fn($user) => EnterpriseUserDTO::fromEntity($user), $users);
    }

    public function updateUser(EnterpriseUserDTO $userDTO): EnterpriseUserDTO
    {
        $user = $this->userRepository->findById($userDTO->id);

        if (!$user) {
            throw new \InvalidArgumentException('User not found');
        }

        $user->setName($userDTO->name);
        $user->setEmail(new Email($userDTO->email));

        if ($userDTO->password) {
            $user->setPassword(new Password($userDTO->password));
        }

        $this->userRepository->save($user);

        return EnterpriseUserDTO::fromEntity($user);
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new \InvalidArgumentException('User not found');
        }

        $company = $user->getCompany();
        $company->removeUser($user);
        $this->userRepository->delete($user);
    }

    public function authenticate(string $email, string $password): ?EnterpriseUserDTO
    {
        $user = $this->userRepository->findByEmail(new Email($email));

        if ($user && $user->verifyPassword($password)) {
            $user->updateLastLogin();
            $this->userRepository->save($user);
            return EnterpriseUserDTO::fromEntity($user);
        }

        return null;
    }
}
