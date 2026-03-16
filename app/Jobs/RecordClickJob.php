<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Jenssegers\Agent\Agent;

class RecordClickJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Link $link,
        public ?string $ipAddress,
        public ?string $userAgent,
        public ?string $referer
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $agent = new Agent;
        $agent->setUserAgent($this->userAgent);

        // Determine device type
        $device = 'desktop';
        if ($agent->isMobile()) {
            $device = 'mobile';
        } elseif ($agent->isTablet()) {
            $device = 'tablet';
        }

        // Record the click
        $this->link->clicks()->create([
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'referer' => $this->referer,
            'device' => $device,
            'browser' => $agent->browser() ?: 'Unknown',
            'os' => $agent->platform() ?: 'Unknown',
            // Country will be resolved via another job in Phase 3
        ]);

        // Increment the denormalized counter
        $this->link->increment('clicks_count');
    }
}
