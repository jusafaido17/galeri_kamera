<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyData extends Command
{
    protected $signature = 'copy:data';
    protected $description = 'Copy data from MySQL to PostgreSQL';

    public function handle()
    {
        $this->info('Starting data migration...');

        $tables = [
            'categories',
            'users',
            'products',
            'carts',
            'orders',
            'order_items',
            'payments',
        ];

        foreach ($tables as $table) {

            $this->info("Copying table: $table");

            $rows = DB::connection('old_mysql')->table($table)->get();

            foreach ($rows as $row) {
                DB::connection('pgsql')->table($table)->insert((array) $row);
            }

            $this->info("Finished: $table");
        }

        $this->info('✅ All data copied successfully!');
    }
}
