<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('shows the preview page for an active link', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com',
        'short_code' => 'preview-test',
        'title' => 'Laravel Framework',
        'is_active' => true,
    ]);

    $response = $this->get('/'.$link->short_code.'+');

    $response->assertStatus(200)
        ->assertViewIs('links.preview')
        ->assertSee('Laravel Framework')
        ->assertSee('https://laravel.com');
});

it('returns 404 for an inactive link on preview', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'short_code' => 'inactive-preview',
        'is_active' => false,
    ]);

    $response = $this->get('/'.$link->short_code.'+');

    $response->assertStatus(404);
});

it('returns 410 for an expired link on preview', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'short_code' => 'expired-preview',
        'is_active' => true,
        'expires_at' => Carbon::now()->subDays(1),
    ]);

    $response = $this->get('/'.$link->short_code.'+');

    $response->assertStatus(410);
});

it('shows password warning if link is password protected on preview', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'short_code' => 'password-preview',
        'password' => \Illuminate\Support\Facades\Hash::make('secret'),
        'is_active' => true,
    ]);

    $response = $this->get('/'.$link->short_code.'+');

    $response->assertStatus(200)
        ->assertSee('Tautan ini dilindungi oleh password');
});
