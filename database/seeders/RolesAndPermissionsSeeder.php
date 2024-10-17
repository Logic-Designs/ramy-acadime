<?php

namespace Database\Seeders;

use App\Traits\ManagesRolesAndPermissionsTrait;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    use ManagesRolesAndPermissionsTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = [
            'admin',
            'user',
            'coach',
            'site manager',
            'dev',
        ];

        foreach ($roles as $role) {
            $this->createRoleIfNotExists($role);
        }
    }
}
