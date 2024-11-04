<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::factory(5)->create()->each(function ($level) {
            LevelSession::factory(rand(5, 8))->create([
                'level_id' => $level->id,
            ]);
        });
    }
}
