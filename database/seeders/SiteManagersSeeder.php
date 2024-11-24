<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SiteManagersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coachRole = Role::firstOrCreate(['name' => 'site manager', 'guard_name' => 'sanctum']);

        User::factory()
            ->count(50)
            ->asSiteManager()
            ->create()
            ->each(function ($user) use ($coachRole) {
                $user->syncRoles($coachRole);
            });
    }
}
