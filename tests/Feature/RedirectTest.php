<?php

use App\Jobs\RecordClickJob;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->link = Link::factory()->create([
        'user_id' => $this->user->id,
        'original_url' => 'https://example.com/target',
        'short_code' => 'abcdef',
        'is_active' => true,
    ]);
});

it('redirects to the original url with a 301 status', function () {
    // Clear cache to ensure fallback to DB works
    Cache::forget('link:abcdef');

    $response = $this->get('/abcdef');

    $response->assertStatus(301)
        ->assertRedirect('https://example.com/target');
});

it('returns 404 for a non-existent short code', function () {
    $response = $this->get('/not-found-123');

    $response->assertStatus(404);
});

it('returns 404 for an inactive link', function () {
    $this->link->update(['is_active' => false]);
    Cache::forget('link:abcdef');

    $response = $this->get('/abcdef');

    $response->assertStatus(404);
});

it('dispatches the record click job when a link is visited', function () {
    Queue::fake();

    $this->get('/abcdef', [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Referer' => 'https://google.com',
    ]);

    Queue::assertPushed(RecordClickJob::class, function ($job) {
        return $job->link->id === $this->link->id
            && $job->userAgent === 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            && $job->referer === 'https://google.com';
    });
});
