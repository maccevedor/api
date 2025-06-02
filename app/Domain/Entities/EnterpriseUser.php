<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class EnterpriseUser
{
    private ?int $id;
    private string $name;
    private Email $email;
    private Password $password;
    private Company $company;
    private ?\DateTime $lastLoginAt;

    public function __construct(
        string $name,
        Email $email,
        Password $password,
        Company $company,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->company = $company;
        $this->lastLoginAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getLastLoginAt(): ?\DateTime
    {
        return $this->lastLoginAt;
    }

    public function updateLastLogin(): void
    {
        $this->lastLoginAt = new \DateTime();
    }

    public function verifyPassword(string $password): bool
    {
        return $this->password->verify($password);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }
}
