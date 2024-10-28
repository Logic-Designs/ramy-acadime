<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->streetName,
            'name_ar' => $this->faker->unique()->word,
            'city_id' => City::inRandomOrder()->value('id') ?? City::factory(),
            'address_en' => $this->faker->address,
            'address_ar' => $this->faker->address,
            'map' => $this->faker->url,
        ];
    }

    public function createOrUpdateLocation($nameEn, $nameAr, $cityId)
    {
        return Location::updateOrCreate(
            ['name_en' => $nameEn, 'name_ar' => $nameAr, 'city_id' => $cityId],
            [
                'address_en' => $this->faker->address,
                'address_ar' => $this->faker->address,
                'map' => $this->faker->url,
            ]
        );
    }
}
