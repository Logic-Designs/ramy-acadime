<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->city,
            'name_ar' => $this->faker->unique()->word,
            'country_id' => Country::inRandomOrder()->value('id') ?? Country::factory(),
        ];
    }

    public function createOrUpdateCity($nameEn, $nameAr, $countryId)
    {
        return City::updateOrCreate(
            ['name_en' => $nameEn, 'name_ar' => $nameAr, 'country_id' => $countryId],
            [
            ]
        );
    }
}
