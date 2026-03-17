<?php

use Bitsoftsol\LaravelAdministration\Http\Controllers\Api\LaravelAdmin\LaravelAdminApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth:sanctum'])->group(function() {

    Route::prefix('crud')->group(function() {
        Route::get('models', [LaravelAdminApiController::class, 'models']);

        Route::get('{crud}', [LaravelAdminApiController::class, 'index'])->name('crud.index');
        Route::post('{crud}', [LaravelAdminApiController::class, 'store'])->name('crud.store');
        Route::get('{crud}/{id}', [LaravelAdminApiController::class, 'show'])->name('crud.show');
        Route::delete('{crud}/{id}', [LaravelAdminApiController::class, 'destroy'])->name('crud.destroy');
    });

});



