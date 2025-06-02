<?php

namespace App\Domain\ValueObjects;

class SubscriptionStatus
{
    public const ACTIVE = 'active';
    public const CANCELLED = 'cancelled';
    public const EXPIRED = 'expired';
    public const PENDING = 'pending';

    private string $value;

    public function __construct(string $status)
    {
        if (!in_array($status, [self::ACTIVE, self::CANCELLED, self::EXPIRED, self::PENDING])) {
            throw new \InvalidArgumentException('Invalid subscription status');
        }
        $this->value = $status;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function equals(SubscriptionStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
