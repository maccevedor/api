<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Feature;
use App\Domain\ValueObjects\Money;

class Plan
{
    private ?int $id;
    private string $name;
    private Money $monthlyPrice;
    private int $userLimit;
    private array $features;

    public function __construct(
        string $name,
        Money $monthlyPrice,
        int $userLimit,
        array $features = [],
        ?int $id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->monthlyPrice = $monthlyPrice;
        $this->userLimit = $userLimit;
        $this->features = $features;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMonthlyPrice(): Money
    {
        return $this->monthlyPrice;
    }

    public function getUserLimit(): int
    {
        return $this->userLimit;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): void
    {
        $this->features[] = $feature;
    }

    public function removeFeature(Feature $feature): void
    {
        $this->features = array_filter(
            $this->features,
            fn(Feature $existingFeature) => !$existingFeature->equals($feature)
        );
    }
}
