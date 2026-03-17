<?php

use Bitsoftsol\LaravelAdministration\Http\Controllers\LaravelAdmin\CrudSchemaController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\LaravelAdmin\LaravelAdminController;
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





Route::middleware('auth')->group(function() {

    Route::prefix('crud')->group(function() {
        Route::get('', [LaravelAdminController::class, 'crud']);
        Route::get('{type}', [LaravelAdminController::class, 'index'])->name('type.index');
        Route::get('{type}/create', [LaravelAdminController::class, 'create'])->name('type.create');
        Route::post('{type}', [LaravelAdminController::class, 'store'])->name('type.store');
        Route::delete('{type}/{id}', [LaravelAdminController::class, 'destroy'])->name('type.destroy');
        Route::get('{type}/{id}/edit', [LaravelAdminController::class, 'edit'])->name('type.edit');
    });

    // Routes for CRUD Schema
    Route::prefix('crud-schema')->group(function() {
        Route::get('', [CrudSchemaController::class, 'list']);
        Route::get('create', [CrudSchemaController::class, 'create']);
        Route::post('create-model', [CrudSchemaController::class, 'createModel']);

        Route::prefix('')->group(function(){
            Route::get('{type}/edit', [CrudSchemaController::class, 'edit']);
            Route::get('{type}/editor', [CrudSchemaController::class, 'editor']);
            Route::post('{type}/editor/save', [CrudSchemaController::class, 'saveEditor']);
            Route::get('{type}/migrate', [CrudSchemaController::class, 'migrate']);
            Route::post('{type}/delete', [CrudSchemaController::class, 'delete']);
            Route::post('{type}/store', [CrudSchemaController::class, 'store']);
        });

    });

});



