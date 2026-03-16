<?php

use App\Models\Link;
use App\Models\User;
use App\Services\LinkService;

it('generates a unique short code of specified length', function () {
    $service = new LinkService;
    $code = $service->generateShortCode(6);

    expect($code)->toBeString()->toHaveLength(6);
});

it('validates correct urls and rejects invalid ones', function () {
    $service = new LinkService;

    expect($service->validateUrl('https://example.com'))->toBeTrue()
        ->and($service->validateUrl('http://test.org/path?param=1'))->toBeTrue()
        ->and($service->validateUrl('not-a-url'))->toBeFalse()
        ->and($service->validateUrl('javascript:alert(1)'))->toBeFalse();
});

it('creates a link for a user and caches it', function () {
    $user = User::factory()->create();
    $service = new LinkService;

    $originalUrl = 'https://devshort.id/demo';
    $title = 'Demo Link';

    $link = $service->createLink($user, $originalUrl, $title);

    expect($link)
        ->toBeInstanceOf(Link::class)
        ->user_id->toBe($user->id)
        ->original_url->toBe($originalUrl)
        ->title->toBe($title)
        ->short_code->not->toBeEmpty();

    // Check if the link exists in the database
    $this->assertDatabaseHas('links', [
        'id' => $link->id,
        'short_code' => $link->short_code,
    ]);

    // Check if it was cached
    $cachedLink = \Illuminate\Support\Facades\Cache::get("link:{$link->short_code}");
    expect($cachedLink)->not->toBeNull()
        ->id->toBe($link->id);
});
