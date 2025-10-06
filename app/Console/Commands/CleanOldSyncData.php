<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OfflineSync;
use Carbon\Carbon;

class CleanOldSyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:clean {--days=30 : Number of days to keep synced data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old synced data older than specified days (default: 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Cleaning synced data older than {$days} days...");

        // Count records to be deleted
        $count = OfflineSync::where('is_synced', true)
            ->where('synced_at', '<', $cutoffDate)
            ->count();

        if ($count === 0) {
            $this->info('No old synced data found to clean.');
            return 0;
        }

        // Delete old synced records
        $deleted = OfflineSync::where('is_synced', true)
            ->where('synced_at', '<', $cutoffDate)
            ->delete();

        $this->info("Successfully cleaned {$deleted} old synced records.");

        // Also clean failed sync records older than 7 days
        $failedCutoffDate = Carbon::now()->subDays(7);
        $failedCount = OfflineSync::where('is_synced', false)
            ->where('created_at', '<', $failedCutoffDate)
            ->count();

        if ($failedCount > 0) {
            $deletedFailed = OfflineSync::where('is_synced', false)
                ->where('created_at', '<', $failedCutoffDate)
                ->delete();
            
            $this->info("Also cleaned {$deletedFailed} old failed sync records.");
        }

        return 0;
    }
}
