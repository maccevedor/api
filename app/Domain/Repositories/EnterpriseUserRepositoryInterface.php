<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Company;
use App\Domain\Entities\EnterpriseUser;
use App\Domain\ValueObjects\Email;

interface EnterpriseUserRepositoryInterface
{
    public function findById(int $id): ?EnterpriseUser;
    public function findByEmail(Email $email): ?EnterpriseUser;
    public function findByCompany(Company $company): array;
    public function save(EnterpriseUser $user): void;
    public function delete(EnterpriseUser $user): void;
}
