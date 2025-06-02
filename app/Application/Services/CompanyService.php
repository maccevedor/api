<?php

namespace App\Application\Services;

use App\Application\DTOs\CompanyDTO;
use App\Application\Interfaces\CompanyServiceInterface;
use App\Domain\Entities\Company;
use App\Domain\Entities\Plan;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Domain\ValueObjects\Email;

class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
        private PlanRepositoryInterface $planRepository
    ) {}

    public function createCompany(CompanyDTO $companyDTO): CompanyDTO
    {
        $company = new Company(
            $companyDTO->name,
            new Email($companyDTO->email)
        );

        $this->companyRepository->save($company);

        return CompanyDTO::fromArray([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'email' => $company->getEmail()->getValue(),
            'active_subscription' => null
        ]);
    }

    public function findCompanyById(int $id): ?CompanyDTO
    {
        $company = $this->companyRepository->findById($id);

        if (!$company) {
            return null;
        }

        $subscription = $company->getActiveSubscription();
        return CompanyDTO::fromArray([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'email' => $company->getEmail()->getValue(),
            'active_subscription' => $subscription ? [
                'plan_id' => $subscription->getPlan()->getId(),
                'starts_at' => $subscription->getStartDate()->format('Y-m-d H:i:s'),
                'ends_at' => $subscription->getEndDate()?->format('Y-m-d H:i:s'),
                'status' => $subscription->getStatus()->getValue()
            ] : null
        ]);
    }

    public function findCompanyByEmail(string $email): ?CompanyDTO
    {
        $company = $this->companyRepository->findByEmail(new Email($email));

        if (!$company) {
            return null;
        }

        $subscription = $company->getActiveSubscription();
        return CompanyDTO::fromArray([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'email' => $company->getEmail()->getValue(),
            'active_subscription' => $subscription ? [
                'plan_id' => $subscription->getPlan()->getId(),
                'starts_at' => $subscription->getStartDate()->format('Y-m-d H:i:s'),
                'ends_at' => $subscription->getEndDate()?->format('Y-m-d H:i:s'),
                'status' => $subscription->getStatus()->getValue()
            ] : null
        ]);
    }

    public function findAllCompanies(): array
    {
        return array_map(
            fn(Company $company) => CompanyDTO::fromArray([
                'id' => $company->getId(),
                'name' => $company->getName(),
                'email' => $company->getEmail()->getValue(),
                'active_subscription' => $company->getActiveSubscription() ? [
                    'plan_id' => $company->getActiveSubscription()->getPlan()->getId(),
                    'starts_at' => $company->getActiveSubscription()->getStartDate()->format('Y-m-d H:i:s'),
                    'ends_at' => $company->getActiveSubscription()->getEndDate()?->format('Y-m-d H:i:s'),
                    'status' => $company->getActiveSubscription()->getStatus()->getValue()
                ] : null
            ]),
            $this->companyRepository->findAll()
        );
    }

    public function updateCompany(CompanyDTO $companyDTO): CompanyDTO
    {
        $company = $this->companyRepository->findById($companyDTO->id);

        if (!$company) {
            throw new \InvalidArgumentException('Company not found');
        }

        $company = new Company(
            $companyDTO->name,
            new Email($companyDTO->email),
            $companyDTO->id
        );

        $this->companyRepository->save($company);

        $subscription = $company->getActiveSubscription();
        return CompanyDTO::fromArray([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'email' => $company->getEmail()->getValue(),
            'active_subscription' => $subscription ? [
                'plan_id' => $subscription->getPlan()->getId(),
                'starts_at' => $subscription->getStartDate()->format('Y-m-d H:i:s'),
                'ends_at' => $subscription->getEndDate()?->format('Y-m-d H:i:s'),
                'status' => $subscription->getStatus()->getValue()
            ] : null
        ]);
    }

    public function deleteCompany(int $id): void
    {
        $company = $this->companyRepository->findById($id);

        if (!$company) {
            throw new \InvalidArgumentException('Company not found');
        }

        $this->companyRepository->delete($company);
    }

    public function subscribeToPlan(int $companyId, int $planId): CompanyDTO
    {
        $company = $this->companyRepository->findById($companyId);
        if (!$company) {
            throw new \InvalidArgumentException('Company not found');
        }

        $plan = $this->planRepository->findById($planId);
        if (!$plan) {
            throw new \InvalidArgumentException('Plan not found');
        }

        $subscription = $company->subscribe($plan);
        $this->companyRepository->save($company);

        return CompanyDTO::fromArray([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'email' => $company->getEmail()->getValue(),
            'active_subscription' => [
                'id' => $subscription->getId(),
                'plan_id' => $subscription->getPlan()->getId(),
                'start_date' => $subscription->getStartDate()->format('Y-m-d H:i:s'),
                'end_date' => $subscription->getEndDate()?->format('Y-m-d H:i:s'),
                'status' => $subscription->getStatus()->getValue()
            ]
        ]);
    }

    public function cancelSubscription(int $companyId): CompanyDTO
    {
        $company = $this->companyRepository->findById($companyId);
        if (!$company) {
            throw new \InvalidArgumentException('Company not found');
        }

        $subscription = $company->getActiveSubscription();
        if ($subscription) {
            $subscription->cancel();
            $this->companyRepository->save($company);
        }

        return CompanyDTO::fromArray([
            'id' => $company->getId(),
            'name' => $company->getName(),
            'email' => $company->getEmail()->getValue(),
            'active_subscription' => null
        ]);
    }
}
