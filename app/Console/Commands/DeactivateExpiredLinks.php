<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeactivateExpiredLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate links that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = \App\Models\Link::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info("Deactivated {$count} expired links.");
    }
}
