<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\ManagesRolesAndPermissionsTrait;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminDevUsersSeeder extends Seeder
{
    use ManagesRolesAndPermissionsTrait;


    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'password' => bcrypt('12345678'),
                'name' => 'Admin User',
                'phone'=> '123456786',
                'first_name'=> 'Admin',
            ]
        );

        $dev = User::firstOrCreate(
            ['email' => 'dev@gmail.com'],
            [
                'name' => 'Dev User',
                'password' => bcrypt('12345678'),
                'phone'=> '123456789',
                'first_name'=> 'Dev',
            ]
        );

        $this->assignRoleToUser($admin, 'admin');
        $this->assignRoleToUser($dev, 'dev');
    }
}

