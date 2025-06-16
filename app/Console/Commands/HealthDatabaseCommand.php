<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class HealthDatabaseCommand extends Command
{
    protected $signature = 'health:database';

    protected $description = 'Verifies that the application can connect to the database. On failure, it logs an emergency message to alert administrators.';

    public function handle(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            Log::emergency('Could not connect to the main database!', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
