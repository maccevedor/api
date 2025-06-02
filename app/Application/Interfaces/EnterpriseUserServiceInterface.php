<?php

namespace App\Application\Interfaces;

use App\Application\DTOs\EnterpriseUserDTO;

interface EnterpriseUserServiceInterface
{
    public function createUser(EnterpriseUserDTO $userDTO): EnterpriseUserDTO;
    public function findUserById(int $id): ?EnterpriseUserDTO;
    public function findUserByEmail(string $email): ?EnterpriseUserDTO;
    public function findUsersByCompany(int $companyId): array;
    public function updateUser(EnterpriseUserDTO $userDTO): EnterpriseUserDTO;
    public function deleteUser(int $id): void;
    public function authenticate(string $email, string $password): ?EnterpriseUserDTO;
}
