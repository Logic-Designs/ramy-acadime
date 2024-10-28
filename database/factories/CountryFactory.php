<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->country,
            'name_ar' => $this->faker->unique()->word,
            'code' => $this->faker->unique()->countryCode,
        ];
    }
}
