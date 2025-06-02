<?php

namespace App\Application\DTOs;

use App\Domain\Entities\EnterpriseUser;

class EnterpriseUserDTO
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public ?string $password,
        public int $companyId,
        public ?string $lastLoginAt = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            companyId: $data['company_id'],
            lastLoginAt: $data['last_login_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->companyId,
            'last_login_at' => $this->lastLoginAt
        ];
    }

    public static function fromEntity(EnterpriseUser $user): self
    {
        return new self(
            id: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail()->getValue(),
            password: null,
            companyId: $user->getCompany()->getId(),
            lastLoginAt: $user->getLastLoginAt()?->format('Y-m-d H:i:s')
        );
    }
}
