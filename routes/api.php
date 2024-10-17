<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);



    Route::middleware('permission:view roles')->group(function () {
        Route::get('roles', [RolePermissionController::class, 'listRolesWithPermissions']);
        Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'listPermissionsForRole']);
        Route::get('permissions', [RolePermissionController::class, 'listPermissions']);
    });

    Route::middleware('permission:update roles')->group(function () {
        Route::post('roles/{roleId}/permissions', [RolePermissionController::class, 'assignPermission']);
        Route::delete('roles/{roleId}/permissions', [RolePermissionController::class, 'removePermission']);
    });

});

