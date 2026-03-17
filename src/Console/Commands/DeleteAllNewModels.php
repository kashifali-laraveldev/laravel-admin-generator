<?php

namespace Bitsoftsol\LaravelAdministration\Console\Commands;

use Illuminate\Console\Command;

class DeleteAllNewModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-admin:delete-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command delete all new models, migrations and tables in database except ignored models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $res = deleteAllModelAndMigrationFiles();
        return Command::SUCCESS;
    }
}
