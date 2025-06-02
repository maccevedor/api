<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Company;
use App\Domain\ValueObjects\Email;

interface CompanyRepositoryInterface
{
    public function findById(int $id): ?Company;
    public function findByEmail(Email $email): ?Company;
    public function findAll(): array;
    public function save(Company $company): void;
    public function delete(Company $company): void;
}
