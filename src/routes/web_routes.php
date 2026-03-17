<?php

use Bitsoftsol\LaravelAdministration\Http\Controllers\Auth\LoginController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\Auth\RegisterController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Bitsoftsol\LaravelAdministration\Http\Controllers\UserController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\AuthGroupController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\AuthUserGroupController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\DashboardController;
use Bitsoftsol\LaravelAdministration\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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

// Clear Cache Route
Route::get('clear-cache/{flag?}', function($flag = null){
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return "Cache cleared successfully";
});

Route::get('api/doc', function(){
    Artisan::call('l5-swagger:generate');
    return redirect()->to(url('api/documentation'));
});

Route::get('home', function(){
    return redirect()->to('/admin');
});

// Auth Routes
// Auth::routes();


Route::get('login', function () {
    return redirect()->to('admin');
})->name('login');

// Admin route, if user is authenticated then it will be login page otherwise it is dashboard page
Route::get('admin', [DashboardController::class, 'dashboard'])->middleware('web');

// Here are admin routes with admin prefix, without crud
Route::middleware(['web', 'auth'])->prefix('admin')->group(function() {

    Route::get('change-password', [ProfileController::class, "changePasswordForm"]);
    Route::post('change-password', [ProfileController::class, "changePassword"]);

    // User Resource Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Auth Group Resource Routes
    Route::get('auth_groups', [AuthGroupController::class, 'index'])->name('auth_groups.index');
    Route::get('auth_groups/create', [AuthGroupController::class, 'create'])->name('auth_groups.create');
    Route::post('auth_groups', [AuthGroupController::class, 'store'])->name('auth_groups.store');
    Route::get('auth_groups/{auth_group}', [AuthGroupController::class, 'show'])->name('auth_groups.show');
    Route::get('auth_groups/{auth_group}/edit', [AuthGroupController::class, 'edit'])->name('auth_groups.edit');
    Route::put('auth_groups/{auth_group}', [AuthGroupController::class, 'update'])->name('auth_groups.update');
    Route::delete('auth_groups/{auth_group}', [AuthGroupController::class, 'destroy'])->name('auth_groups.destroy');

    // Auth User Group Resource Routes
    Route::get('auth_user_group', [AuthUserGroupController::class, 'index'])->name('auth_user_group.index');
    Route::get('auth_user_group/create', [AuthUserGroupController::class, 'create'])->name('auth_user_group.create');
    Route::post('auth_user_group', [AuthUserGroupController::class, 'store'])->name('auth_user_group.store');
    Route::get('auth_user_group/{auth_user_group}', [AuthUserGroupController::class, 'show'])->name('auth_user_group.show');
    Route::get('auth_user_group/{auth_user_group}/edit', [AuthUserGroupController::class, 'edit'])->name('auth_user_group.edit');
    Route::put('auth_user_group/{auth_user_group}', [AuthUserGroupController::class, 'update'])->name('auth_user_group.update');
    Route::delete('auth_user_group/{auth_user_group}', [AuthUserGroupController::class, 'destroy'])->name('auth_user_group.destroy');

});



