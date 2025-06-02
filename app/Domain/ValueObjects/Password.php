<?php

namespace App\Domain\ValueObjects;

class Password
{
    private string $hashedValue;

    public function __construct(string $password)
    {
        $this->hashedValue = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify(string $password): bool
    {
        return password_verify($password, $this->hashedValue);
    }

    public function getHashedValue(): string
    {
        return $this->hashedValue;
    }

    public static function fromHash(string $hash): self
    {
        $instance = new self('');
        $instance->hashedValue = $hash;
        return $instance;
    }
}
