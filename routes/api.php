<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailableTimesController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\LevelSessionController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\SessionTimeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('countries', [CountryController::class, 'index']);
Route::get('cities', [CityController::class, 'index']);
Route::get('locations', [LocationController::class, 'index']);

Route::get('levels', [LevelController::class, 'index']);
Route::get('level-sessions', [LevelSessionController::class, 'index']);


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('session-times', [SessionTimeController::class, 'index']);



    Route::middleware('role:admin')->group(function () {
        Route::get('roles', [RolePermissionController::class, 'listRolesWithPermissions']);
        Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'listPermissionsForRole']);
        Route::get('permissions', [RolePermissionController::class, 'listPermissions']);
        Route::post('roles/{roleId}/permissions', [RolePermissionController::class, 'assignPermission']);
        Route::delete('roles/{roleId}/permissions', [RolePermissionController::class, 'removePermission']);

        Route::apiResource('countries', CountryController::class)->except(['index']);
        Route::apiResource('cities', CityController::class)->except(['index']);

        Route::apiResource('locations', LocationController::class)->except(['index']);
        Route::post('locations/{location}/managers', [LocationController::class, 'assignManagers']);
        Route::get('locations/{location}/managers', [LocationController::class, 'getSiteManagers']);
        Route::delete('/locations/{location}/managers', [LocationController::class, 'removeManager']);
        Route::post('locations/{location}/coaches', [LocationController::class, 'assignCoachs']);
        Route::get('locations/{location}/coaches', [LocationController::class, 'getCoachs']);
        Route::delete('/locations/{location}/coaches', [LocationController::class, 'removeCoach']);

        Route::apiResource('levels', LevelController::class)->except(['index']);
        Route::apiResource('level-sessions', LevelSessionController::class)->except(['index']);
        Route::apiResource('session-times', SessionTimeController::class)->except(['index']);




    });


    Route::prefix('users')->group(function () {
        Route::middleware('role:user')->group(function(){
            Route::get('/children', [UserController::class, 'getChildren']);
            Route::post('/children', [UserController::class, 'storeChildUser']);
            Route::delete('/children/{child}', [UserController::class, 'detachChildUser'])
            ->can('deleteChild', 'child');
        });

        Route::get('', [UserController::class, 'index']);
        Route::post('', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);

    });


    Route::prefix('bookings')->group(function() {
        Route::get('', [BookingController::class, 'index']);
        Route::post('', [BookingController::class, 'store']);
        Route::get('/my', [BookingController::class, 'myBookings']);
        Route::get('/{booking}', [BookingController::class, 'show']);
        Route::get('/user/{userId}', [BookingController::class, 'userBookings']);
        Route::put('/{booking}', [BookingController::class, 'update']);
        Route::delete('/{booking}', [BookingController::class, 'destroy']);
        Route::get('/time/{bookingTime}', [BookingController::class, 'showBookingTime']);
        Route::put('/time/{bookingTime}', [BookingController::class, 'updateBookingTime']);
    });


    Route::get('/available-times/{level}', [AvailableTimesController::class, 'getAvailableTimes']);



});

