<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\SubscriptionStatus;

class Company
{
    private ?int $id;
    private string $name;
    private Email $email;
    private ?Subscription $activeSubscription;
    private array $subscriptions;
    private array $users;

    public function __construct(
        string $name,
        Email $email,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->activeSubscription = null;
        $this->subscriptions = [];
        $this->users = [];
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

    public function getActiveSubscription(): ?Subscription
    {
        return $this->activeSubscription;
    }

    public function getSubscriptions(): array
    {
        return $this->subscriptions;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function subscribe(Plan $plan): Subscription
    {
        if ($this->activeSubscription && $this->activeSubscription->isActive()) {
            $this->activeSubscription->cancel();
        }

        $subscription = new Subscription(
            $this,
            $plan,
            new SubscriptionStatus(SubscriptionStatus::ACTIVE),
            new \DateTime()
        );

        $this->subscriptions[] = $subscription;
        $this->activeSubscription = $subscription;

        return $subscription;
    }

    public function addUser(EnterpriseUser $user): void
    {
        if (!$this->activeSubscription) {
            throw new \InvalidArgumentException('Company must have an active subscription to add users');
        }

        if (count($this->users) >= $this->activeSubscription->getPlan()->getUserLimit()) {
            throw new \InvalidArgumentException('User limit reached for current plan');
        }

        $this->users[] = $user;
    }

    public function removeUser(EnterpriseUser $user): void
    {
        $this->users = array_filter(
            $this->users,
            fn(EnterpriseUser $existingUser) => $existingUser->getId() !== $user->getId()
        );
    }

    public function canAddMoreUsers(): bool
    {
        if (!$this->activeSubscription) {
            return false;
        }

        return count($this->users) < $this->activeSubscription->getPlan()->getUserLimit();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription !== null;
    }
}
