<?php

use App\Http\Controllers\LanguageManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/languages', [LanguageManagementController::class, 'index'])->name('languages.index');
Route::post('/languages/update', [LanguageManagementController::class, 'update'])->name('languages.update');
Route::post('/languages/store', [LanguageManagementController::class, 'store'])->name('languages.store');
Route::delete('/languages/delete', [LanguageManagementController::class, 'destroy'])->name('languages.delete');
