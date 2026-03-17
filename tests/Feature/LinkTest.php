<?php

use App\Jobs\RecordClickJob;
use App\Models\Click;
use App\Models\Link;
use App\Models\User;

it('parses user agent correctly in the record click job', function () {
    $user = User::factory()->create();
    $link = Link::factory()->create([
        'user_id' => $user->id,
        'clicks_count' => 0,
    ]);

    $job = new RecordClickJob(
        $link,
        '8.8.8.8', // Use public IP for GeoIP mock
        'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1',
        'https://twitter.com/some/path'
    );

    // Mock Location Facade
    $position = new \Stevebauman\Location\Position;
    $position->countryCode = 'US';
    \Stevebauman\Location\Facades\Location::shouldReceive('get')
        ->with('8.8.8.8')
        ->andReturn($position);

    // Execute the job synchronously
    $job->handle();

    // Check link click count incremented
    expect($link->fresh()->clicks_count)->toBe(1);

    // Check click record created accurately
    $click = Click::where('link_id', $link->id)->first();

    expect($click)
        ->not->toBeNull()
        ->ip_address->toBe('8.8.8.8')
        ->referer->toBe('twitter.com')
        ->device->toBe('mobile')
        ->browser->toBe('Safari')
        ->os->toBe('iOS')
        ->country->toBe('US');
});
