<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\EnterpriseUser;
use App\Domain\Entities\Company;
use App\Domain\Repositories\EnterpriseUserRepositoryInterface;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Models\EnterpriseUser as EnterpriseUserModel;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class EloquentEnterpriseUserRepository implements EnterpriseUserRepositoryInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {}

    public function findById(int $id): ?EnterpriseUser
    {
        $model = EnterpriseUserModel::with('company')->find($id);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByEmail(Email $email): ?EnterpriseUser
    {
        $model = EnterpriseUserModel::with('company')
            ->where('email', $email->getValue())
            ->first();

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByCompany(Company $company): array
    {
        return EnterpriseUserModel::with('company')
            ->where('company_id', $company->getId())
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(EnterpriseUser $user): void
    {
        $model = $user->getId()
            ? EnterpriseUserModel::find($user->getId())
            : new EnterpriseUserModel();

        $model->name = $user->getName();
        $model->email = $user->getEmail()->getValue();
        $model->password = $user->getPassword()->getHashedValue();
        $model->company_id = $user->getCompany()->getId();
        $model->last_login_at = $user->getLastLoginAt();
        $model->save();
    }

    public function delete(EnterpriseUser $user): void
    {
        EnterpriseUserModel::destroy($user->getId());
    }

    private function toEntity(EnterpriseUserModel $model): EnterpriseUser
    {
        $company = $this->companyRepository->findById($model->company_id);

        if (!$company) {
            throw new \RuntimeException('Company not found for user');
        }

        return new EnterpriseUser(
            $model->name,
            new Email($model->email),
            new Password($model->password),
            $company,
            $model->id
        );
    }
}
