<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RolePermissionRequest;
use App\Traits\ManagesRolesAndPermissionsTrait;
use Illuminate\Support\Facades\Response;

class RolePermissionController extends Controller
{
    use ManagesRolesAndPermissionsTrait;


    public function listRolesWithPermissions()
    {
        $roles = $this->getRoles();
        return Response::success('Roles retrieved successfully.', [
            'roles' => $roles,
        ]);
    }

    public function listPermissions()
    {
        $permissions = $this->getPermissions();

        return Response::success('Permissions retrieved successfully.', [
            'permissions' => $permissions,
        ]);
    }

    public function assignPermission(RolePermissionRequest $request, $roleId)
    {
        $this->assignPermissionsToRole($roleId, $request->permissions);

        return Response::success('Permissions assigned to role successfully.');
    }

    public function removePermission(RolePermissionRequest $request, $roleId)
    {
        $this->removePermissionsFromRole($roleId, $request->permissions);

        return Response::success('Permissions removed from role successfully.');
    }

    public function listPermissionsForRole($roleId)
    {

        $permissions = $this->getPermissionsForRole($roleId);


        return Response::success('Permissions retrieved successfully.', [
            'permissions' => $permissions,
        ]);
    }
}
