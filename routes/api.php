<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::middleware('role:admin')->group(function () {
        Route::get('roles', [RolePermissionController::class, 'listRolesWithPermissions']);
        Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'listPermissionsForRole']);
        Route::get('permissions', [RolePermissionController::class, 'listPermissions']);
        Route::post('roles/{roleId}/permissions', [RolePermissionController::class, 'assignPermission']);
        Route::delete('roles/{roleId}/permissions', [RolePermissionController::class, 'removePermission']);

        Route::apiResource('countries', CountryController::class);
        Route::apiResource('locations', LocationController::class);

    });


    Route::prefix('users')->group(function () {
        Route::middleware('role:user')->group(function(){
            Route::get('/children', [UserController::class, 'getChildren']);
            Route::post('/children', [UserController::class, 'storeChildUser']);
            Route::delete('/children/{child}', [UserController::class, 'detachChildUser'])
            ->can('deleteChild', 'child');
        });

        Route::get('', [UserController::class, 'index'])->can('list users');
        Route::post('', [UserController::class, 'store'])->can('create users');
        Route::get('/{user}', [UserController::class, 'show'])->can('list users');
        Route::put('/{user}', [UserController::class, 'update'])->can('update users');
        Route::delete('/{user}', [UserController::class, 'destroy'])->can('delete users');


    });



});

