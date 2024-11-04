<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Level;
use App\Models\LevelPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->word,
            'name_ar' => $this->faker->unique()->word,
            'description_en' => $this->faker->sentence,
            'description_ar' => $this->faker->sentence,
            'min_age' => $this->faker->numberBetween(5, 18),
            'gender' => $this->faker->randomElement(['male', 'female', 'both']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Level $level) {
            $countries = Country::all()->isEmpty() ? Country::factory(3)->create() : Country::all();

            foreach ($countries as $country) {
                LevelPrice::create([
                    'level_id' => $level->id,
                    'country_id' => $country->id,
                    'price' => $this->faker->randomFloat(2, 100, 1000),
                ]);
            }
        });
    }
}
