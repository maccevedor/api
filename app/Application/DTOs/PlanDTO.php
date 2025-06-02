<?php

namespace App\Application\DTOs;

class PlanDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly float $monthlyPrice,
        public readonly int $userLimit,
        public readonly array $features
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            monthlyPrice: $data['monthly_price'],
            userLimit: $data['user_limit'],
            features: $data['features'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'monthly_price' => $this->monthlyPrice,
            'user_limit' => $this->userLimit,
            'features' => $this->features,
        ];
    }
}
