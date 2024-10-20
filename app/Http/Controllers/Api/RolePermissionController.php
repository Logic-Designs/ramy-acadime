<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RolePermissionRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\PermissionResource;
use App\Traits\ManagesRolesAndPermissionsTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Response;

class RolePermissionController extends Controller
{
    use ManagesRolesAndPermissionsTrait;

    /**
     * List all roles with their associated permissions.
     */
    public function listRolesWithPermissions()
    {
        // Retrieve all roles with their permissions using a resource collection
        $roles = Role::with('permissions')->get();

        return Response::success('Roles retrieved successfully.', [
            'roles' => RoleResource::collection($roles),
        ]);
    }

    /**
     * List all available permissions.
     */
    public function listPermissions()
    {
        // Retrieve all permissions using a resource collection
        $permissions = Permission::all();

        return Response::success('Permissions retrieved successfully.', [
            'permissions' => PermissionResource::collection($permissions),
        ]);
    }

    /**
     * Assign multiple permissions to a role.
     */
    public function assignPermission(RolePermissionRequest $request, $roleId)
    {
        $this->assignPermissionsToRole($roleId, $request->permissions);

        return Response::success('Permissions assigned to role successfully.');
    }

    /**
     * Remove multiple permissions from a role.
     */
    public function removePermission(RolePermissionRequest $request, $roleId)
    {
        $this->removePermissionsFromRole($roleId, $request->permissions);

        return Response::success('Permissions removed from role successfully.');
    }

    /**
     * List all permissions for a specific role.
     */
    public function listPermissionsForRole($roleId)
    {
        // Retrieve the role with its permissions
        $role = Role::with('permissions')->findOrFail($roleId);

        return Response::success('Permissions retrieved successfully.', [
            'permissions' => PermissionResource::collection($role->permissions),
        ]);
    }
}
