<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\EnterpriseUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EnterpriseUserFactory extends Factory
{
    protected $model = EnterpriseUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'company_id' => Company::factory(),
            'last_login_at' => $this->faker->optional()->dateTimeThisMonth(),
        ];
    }
}
