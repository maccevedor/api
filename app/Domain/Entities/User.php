<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class User
{
    private int $id;
    private string $name;
    private Email $email;
    private Password $password;
    private ?\DateTime $emailVerifiedAt;
    private ?string $rememberToken;

    public function __construct(
        string $name,
        Email $email,
        Password $password
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->emailVerifiedAt = null;
        $this->rememberToken = null;
    }

    public function getId(): int
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

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(?string $token): void
    {
        $this->rememberToken = $token;
    }

    public function markEmailAsVerified(): void
    {
        $this->emailVerifiedAt = new \DateTime();
    }
}
