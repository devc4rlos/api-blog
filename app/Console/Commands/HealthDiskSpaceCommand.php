<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HealthDiskSpaceCommand extends Command
{
    protected $signature = 'health:disk-space';

    protected $description = 'Checks the percentage of free disk space. If it falls below 5%, an alert is logged to warn about critically low space.';

    public function handle(): void
    {
        $storagePath = storage_path('/');

        $freeSpace = disk_free_space($storagePath);
        $totalSpace = disk_total_space($storagePath);
        $freeSpacePercent = round(($freeSpace / $totalSpace) * 100, 2);

        if ($freeSpacePercent < 5) {
            Log::alert('Critically low disk space!', [
                'free_percent' => $freeSpacePercent
            ]);
        }
    }
}
