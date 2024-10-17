<?php

// app/Traits/ManagesRolesAndPermissionsTrait.php

namespace App\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

trait ManagesRolesAndPermissionsTrait
{
    // List all roles with permissions
    public function listRolesWithPermissions()
    {
        return Role::with('permissions')->where('guard_name', 'sanctum')->get();
    }

    // List all permissions
    public function listPermissions()
    {
        return Permission::where('guard_name', 'sanctum')->get();
    }

    // Assign permissions to a role
    public function assignPermissions($roleId, $permissions)
    {
        $role = Role::findById($roleId, 'sanctum');

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => [__('Role not found.')],
            ]);
        }

        $role->givePermissionTo($permissions);
    }

    public function removePermissions($roleId, $permissions)
    {
        $role = Role::findById($roleId, 'sanctum');

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => [__('Role not found.')],
            ]);
        }

        $role->revokePermissionTo($permissions);
    }

    public function listPermissionsForRole($roleId)
    {
        $role = Role::findById($roleId, 'sanctum');

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => [__('Role not found.')],
            ]);
        }

        return $role->permissions;
    }

    public function createRoleIfNotExists($roleName)
    {
        return Role::firstOrCreate(
            ['name' => $roleName, 'guard_name' => 'sanctum']
        );
    }

    public function createPermissionIfNotExists($permissionName)
    {
        return Permission::firstOrCreate(
            ['name' => $permissionName, 'guard_name' => 'sanctum']
        );
    }


    public function assignRoleToUser($user, $roleName)
    {
        $role = $this->createRoleIfNotExists($roleName);

        if (!$user->hasRole($roleName)) {
            $user->assignRole($role);
        }
    }

    public function assignPermissionToRole($roleId, $permissions)
    {
        $role = Role::findById($roleId, 'sanctum');

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => [__('Role not found.')],
            ]);
        }

        foreach ($permissions as $permissionName) {
            $this->createPermissionIfNotExists($permissionName);
            $role->givePermissionTo($permissionName);
        }
    }

    public function assignPermissionToRoleByName($roleName, $permissions)
    {
        $role = $this->createRoleIfNotExists($roleName);

        foreach ($permissions as $permissionName) {
            $this->createPermissionIfNotExists($permissionName);
            $role->givePermissionTo($permissionName);
        }
    }
}
