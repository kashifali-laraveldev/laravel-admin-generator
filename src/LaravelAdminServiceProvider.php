<?php

namespace Bitsoftsol\LaravelAdministration;

use Database\Seeders\UserSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    // protected $listen = [
    //     'Illuminate\Database\Events\MigrationsEnded' => [
    //         'Listeners\UpdateAppModelAndAuthPermission',
    //     ],
    // ];
    public function register(): void
    {
        // Controllers web.php
        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\DashboardController');

        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\UserController');

        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\AuthGroupController');

        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\AuthUserGroupController');

        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\ProfileController');

        // // Controllers used in api.php
        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\Api\Auth\AuthController');

        // Controllers used in api_laravel_admin.php
        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\Api\LaravelAdmin\LaravelAdminApiController');

        // // Controllers used in laravel_admin.php file
        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\LaravelAdmin\CrudSchemaController');

        // $this->app->make('Bitsoftsol\LaravelAdministration\Http\Controllers\LaravelAdmin\LaravelAdminController');

        // $this->app->make('Bitsoftsol\LaravelAdministration\database\seeders\DatabaseSeeder');

        $this->loadViewsFrom(__DIR__.'/resources/views/', 'laravel-admin');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Register your command
        $this->commands([
            Console\Commands\CustomMigrate::class,
            Console\Commands\CreateSuperuser::class,
            Console\Commands\DeleteAllNewModels::class,
        ]);

        // Artisan::call('migrate:update-app-models-auth-permission');

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes folder
        // $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        // $this->loadRoutesFrom(__DIR__.'/routes/api_routes.php');

        // $this->loadRoutesFrom(__DIR__.'/routes/laravel_admin.php');

        // Load public folder and publish to access static files
        $this->publishes([
            __DIR__.'/public' => public_path('Bitsoftsol/LaravelAdministration'),
        ], 'public');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], 'migrations');


        $this->publishes([
            __DIR__.'/database/seeders' => database_path('seeders'),
        ], 'seeders');

        $this->publishes([
            __DIR__.'/Http/Controllers/Auth' => app_path('Http/Controllers/Auth'),
        ], 'seeders');


        $seed_list = ['/database/seeders/UserSeeder.php'];

        $this->loadSeeders($seed_list);

        $this->app->events->listen('Illuminate\Database\Events\MigrationsEnded', 'Bitsoftsol\LaravelAdministration\Listeners\UpdateAppModelAndAuthPermission');


        Route::middleware('web')
            ->namespace('BitsoftSol\\LaravelAdministration')
            ->group((__DIR__.'\routes/web_routes.php'));

        Route::middleware('web')
            ->prefix('admin')
            ->namespace('BitsoftSol\\LaravelAdministration')
            ->group((__DIR__.'\routes/laravel_admin.php'));

        Route::middleware('api')
            ->prefix('api')
            ->namespace('BitsoftSol\\LaravelAdministration')
            ->group((__DIR__.'\routes/api_routes.php'));

        Route::middleware('api')
            ->prefix('api/admin')
            ->namespace('BitsoftSol\\LaravelAdministration')
            ->group((__DIR__.'\routes/api_laravel_admin.php'));

    }

    protected function loadSeeders($seed_list)
    {
        $this->callAfterResolving(DatabaseSeeder::class, function ($seeder) use ($seed_list) {
            $seeder->call(UserSeeder::class);
        });
    }
}
