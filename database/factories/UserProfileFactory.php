<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition()
    {
        return [
            'address' => $this->faker->address,
            'bio' => $this->faker->text(200),
            'birthday' => $this->faker->date,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'city_id' => City::inRandomOrder()->first()->id ?? City::factory(),
        ];
    }
}
