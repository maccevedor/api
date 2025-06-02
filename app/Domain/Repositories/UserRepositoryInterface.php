<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function save(User $user): void;
    public function delete(User $user): void;
}
