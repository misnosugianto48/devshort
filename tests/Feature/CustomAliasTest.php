<?php

use App\Models\Link;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows a user to create a link with a valid custom alias', function () {
    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'custom_alias' => 'my-custom-url',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('status');

    $this->assertDatabaseHas('links', [
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com',
        'short_code' => 'my-custom-url',
    ]);
});

it('rejects custom aliases that have been taken', function () {
    Link::factory()->create(['short_code' => 'taken-alias']);

    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'custom_alias' => 'taken-alias',
    ]);

    $response->assertSessionHasErrors(['custom_alias']);
});

it('rejects system-reserved aliases', function () {
    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'custom_alias' => 'login',
    ]);

    $response->assertSessionHasErrors(['custom_alias']);
});

it('rejects aliases with special characters', function () {
    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'custom_alias' => 'my alias!',
    ]);

    $response->assertSessionHasErrors(['custom_alias']);
});
