<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'remember_token' => Str::random(10),
        ];
    }

    public function withProfile()
    {
        return $this->has(
            \App\Models\UserProfile::factory(),
            'profile'
        );
    }

    public function asCoach()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone' => $this->faker->phoneNumber,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'remember_token' => Str::random(10),
            ];
        });
    }

    public function asSiteManager()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone' => $this->faker->phoneNumber,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'remember_token' => Str::random(10),
            ];
        });
    }

}
