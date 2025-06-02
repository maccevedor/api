<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\SubscriptionStatus;

class Subscription
{
    private int $id;
    private Company $company;
    private Plan $plan;
    private SubscriptionStatus $status;
    private \DateTime $startDate;
    private ?\DateTime $endDate;

    public function __construct(
        Company $company,
        Plan $plan,
        SubscriptionStatus $status,
        \DateTime $startDate,
        ?\DateTime $endDate = null
    ) {
        $this->company = $company;
        $this->plan = $plan;
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }

    public function getStatus(): SubscriptionStatus
    {
        return $this->status;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function cancel(): void
    {
        $this->status = new SubscriptionStatus(SubscriptionStatus::CANCELLED);
        $this->endDate = new \DateTime();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}
