<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DbReset extends Command
{
    protected $signature = 'db-reset';

    protected $description = 'Drop all tables, run migrations, and seed the database';

    public function handle()
    {
        if (app()->isProduction()) {
            $this->error('This command cannot be run in production.');

            return 1;
        }

        $this->info('Dropping all tables and running migrations...');
        $this->call('migrate:fresh');

        $this->info('Clearing uploaded pdfs...');
        Storage::disk('local')->deleteDirectory('pdfs');

        $this->info('Seeding database...');
        $this->call('db:seed');

        $this->info('Database reset complete.');

        return 0;
    }
}
