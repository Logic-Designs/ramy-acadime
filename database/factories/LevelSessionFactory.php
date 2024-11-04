<?php

namespace Database\Factories;

use App\Models\Level;
use App\Models\LevelSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class LevelSessionFactory extends Factory
{
    protected $model = LevelSession::class;

    public function definition()
    {
        return [
            'name_en' => $this->faker->unique()->word,
            'name_ar' => $this->faker->unique()->word,
            'description_en' => $this->faker->sentence,
            'description_ar' => $this->faker->sentence,
            'level_id' => Level::inRandomOrder()->value('id') ?? Level::factory(),
        ];
    }
}
