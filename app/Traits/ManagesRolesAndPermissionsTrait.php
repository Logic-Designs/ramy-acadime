<?php

// app/Traits/ManagesRolesAndPermissionsTrait.php

namespace App\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

trait ManagesRolesAndPermissionsTrait
{

    public function getRoles()
    {
        return Role::with('permissions')->where('guard_name', 'sanctum')->get();
    }

    // List all permissions
    public function getPermissions()
    {
        return Permission::where('guard_name', 'sanctum')->get();
    }

    // Assign permissions to a role
    public function assignPermissionsToRole($roleId, $permissions)
    {
        $role = Role::findById($roleId, 'sanctum');

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => [__('Role not found.')],
            ]);
        }

        $role->givePermissionTo($permissions);
    }

    public function removePermissionsFromRole($roleId, $permissions)
    {
        $role = Role::findById($roleId, 'sanctum');

        if (!$role) {
            throw ValidationException::withMessages([
                'role' => [__('Role not found.')],
            ]);
        }

        $role->revokePermissionTo($permissions);
    }

    public function getPermissionsForRole($roleId)
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

    public function assignPermissionsToRoleByName($roleName, $permissions)
    {
        $role = $this->createRoleIfNotExists($roleName);

        foreach ($permissions as $permissionName) {
            $this->createPermissionIfNotExists($permissionName);
            $role->givePermissionTo($permissionName);
        }
    }
}
