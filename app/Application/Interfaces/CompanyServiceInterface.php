<?php

namespace App\Application\Interfaces;

use App\Application\DTOs\CompanyDTO;

interface CompanyServiceInterface
{
    public function createCompany(CompanyDTO $companyDTO): CompanyDTO;
    public function findCompanyById(int $id): ?CompanyDTO;
    public function findCompanyByEmail(string $email): ?CompanyDTO;
    public function findAllCompanies(): array;
    public function updateCompany(CompanyDTO $companyDTO): CompanyDTO;
    public function deleteCompany(int $id): void;
    public function subscribeToPlan(int $companyId, int $planId): CompanyDTO;
    public function cancelSubscription(int $companyId): CompanyDTO;
}
