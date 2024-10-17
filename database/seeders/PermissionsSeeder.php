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
            $this->createPermissionIfNotExists("list {$resource}");
            $this->createPermissionIfNotExists("create {$resource}");
            $this->createPermissionIfNotExists("update {$resource}");
            $this->createPermissionIfNotExists("delete {$resource}");
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

        $this->assignPermissionToRoleByName('admin', Permission::all()->pluck('name')->toArray());

        $this->assignPermissionToRoleByName('dev', ['view logs']);
    }

}
