<?php

namespace Bitsoftsol\LaravelAdministration\Console\Commands;

use Illuminate\Support\Facades\Schema;
use Bitsoftsol\LaravelAdministration\Models\AppModel;
use Bitsoftsol\LaravelAdministration\Models\AuthPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CustomMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:update-app-models-auth-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the AppModel and AuthPermision models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Run the original migrate command
        Artisan::call('migrate');
        if (Schema::hasTable('app_models') && Schema::hasTable('auth_permissions')) {
            $current_models = AppModel::pluck('model');
            $tables = collect(DB::select('SHOW TABLES'))->map(function ($table) {
                return collect($table)->values()->first();
            });

            // Identify models that no longer exist as tables in the database
            $models_to_delete = $current_models->diff($tables);
            foreach ($models_to_delete as $model_to_delete) {
                AppModel::where('model', $model_to_delete)->delete();
            }
            // Filter out tables that aren't models (like migrations table etc.) if needed
            foreach ($tables as $table) {
                // C[heck if the model already exists in app_models
                $app_model = AppModel::firstOrCreate(['model' => $table]);

                // For simplicity, let's say you create three default permissions for each model
                $permissions = ['add', 'change', 'view', 'delete'];

                foreach ($permissions as $permission) {
                    AuthPermission::firstOrCreate([
                        'app_model_id' => $app_model->id,
                        'name' => "Can add {$table} entry",
                        'codename' => "{$permission}_{$table}"
                    ]);
                }
            }
        }
    }
}
