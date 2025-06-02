<?php

namespace App\Domain\ValueObjects;

class Feature
{
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Feature name cannot be empty');
        }
        $this->name = $name;
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function equals(Feature $other): bool
    {
        return $this->name === $other->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
