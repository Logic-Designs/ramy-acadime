<?php

namespace Database\Seeders;

use App\Traits\ManagesRolesAndPermissionsTrait;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    use ManagesRolesAndPermissionsTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resources = [
            'users',
        ];

        // Create CRUD permissions for each resource
        foreach ($resources as $resource) {
            $this->createCRUDPermission($resource);
        }

        // General permissions
        $generalPermissions = [
            'view dashboard',
            'view logs',
            'view roles',
            'update roles',
        ];

        foreach ($generalPermissions as $permission) {
            $this->createPermissionIfNotExists($permission);
        }

        $this->assignPermissionsToRoleByName('admin', Permission::all()->pluck('name')->toArray());

        $this->assignPermissionsToRoleByName('dev', ['view logs']);
    }

}
