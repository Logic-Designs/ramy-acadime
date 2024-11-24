<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CoachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coachRole = Role::firstOrCreate(['name' => 'coach', 'guard_name' => 'sanctum']);

        User::factory()
            ->count(50)
            ->asCoach()
            ->create()
            ->each(function ($user) use ($coachRole) {
                $user->syncRoles($coachRole);
            });
    }
}
