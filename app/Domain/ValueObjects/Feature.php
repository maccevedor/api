<?php

namespace App\Domain\ValueObjects;

class Feature
{
    private ?int $id;
    private string $name;
    private string $description;

    public function __construct(
        string $name,
        string $description,
        ?int $id = null
    ) {
        if (empty($name)) {
            throw new \InvalidArgumentException('Feature name cannot be empty');
        }
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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
        return $this->name === $other->name && $this->description === $other->description;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
