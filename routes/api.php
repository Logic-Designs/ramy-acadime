<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\UserController;
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


    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index'])->can('list users');
        Route::post('', [UserController::class, 'store'])->can('create users');
        Route::get('/{user}', [UserController::class, 'show'])->can('list users');
        Route::put('/{user}', [UserController::class, 'update'])->can('update users');
        Route::delete('/{user}', [UserController::class, 'destroy'])->can('delete users');
    });

});

