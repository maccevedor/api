<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use Illuminate\Support\Facades\DB;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $userData = DB::table('users')->find($id);

        if (!$userData) {
            return null;
        }

        return $this->createUserFromData($userData);
    }

    public function findByEmail(Email $email): ?User
    {
        $userData = DB::table('users')
            ->where('email', $email->getValue())
            ->first();

        if (!$userData) {
            return null;
        }

        return $this->createUserFromData($userData);
    }

    public function save(User $user): void
    {
        DB::table('users')->updateOrInsert(
            ['id' => $user->getId()],
            [
                'name' => $user->getName(),
                'email' => $user->getEmail()->getValue(),
                'password' => $user->getPassword()->getHashedValue(),
                'email_verified_at' => $user->getEmailVerifiedAt(),
                'remember_token' => $user->getRememberToken(),
            ]
        );
    }

    public function delete(User $user): void
    {
        DB::table('users')->where('id', $user->getId())->delete();
    }

    private function createUserFromData(object $data): User
    {
        $user = new User(
            $data->name,
            new Email($data->email),
            Password::fromHash($data->password)
        );

        if ($data->email_verified_at) {
            $user->markEmailAsVerified();
        }

        if ($data->remember_token) {
            $user->setRememberToken($data->remember_token);
        }

        return $user;
    }
}
