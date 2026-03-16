<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows a user to create a link with an expiration date', function () {
    $futureDate = Carbon::now()->addDays(5)->format('Y-m-d\TH:i');

    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'expires_at' => $futureDate,
    ]);

    $response->assertRedirect()
        ->assertSessionHas('status');

    $this->assertDatabaseHas('links', [
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com',
    ]);
});

it('rejects an expiration date in the past', function () {
    $pastDate = Carbon::now()->subDays(5)->format('Y-m-d\TH:i');

    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'expires_at' => $pastDate,
    ]);

    $response->assertSessionHasErrors(['expires_at']);
});

it('redirects to 410 gone error page if link is past expiration date', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com',
        'short_code' => 'expired-link-test',
        'expires_at' => Carbon::now()->subMinutes(10), // Expired 10 mins ago
        'is_active' => true,
    ]);

    // Force flush the cache just in case previous runs stored null here
    \Illuminate\Support\Facades\Cache::flush();

    $response = $this->get('/'.$link->short_code);

    $response->assertStatus(410);
});

it('successfully runs the deactivate command for expired links', function () {
    // Create an expired active link
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'expires_at' => Carbon::now()->subDays(1),
        'is_active' => true,
    ]);

    $this->artisan('links:deactivate-expired')
        ->assertSuccessful()
        ->expectsOutputToContain('Deactivated 1 expired links.');

    $this->assertDatabaseHas('links', [
        'id' => $link->id,
        'is_active' => false,
    ]);
});
