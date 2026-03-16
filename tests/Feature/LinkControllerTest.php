<?php

use App\Models\Link;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->link = Link::factory()->create([
        'user_id' => $this->user->id,
        'original_url' => 'https://example.com/target',
        'title' => 'My Test Link',
    ]);
});

it('prevents guests from accessing the dashboard', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

it('allows verified users to access the dashboard', function () {
    $response = $this->actingAs($this->user)->get('/dashboard');
    $response->assertStatus(200)
        ->assertViewIs('dashboard')
        ->assertSee('Overview');
});

it('prevents guests from accessing the links index', function () {
    $response = $this->get('/links');
    $response->assertRedirect('/login');
});

it('allows users to view their links', function () {
    $response = $this->actingAs($this->user)->get('/links');
    $response->assertStatus(200)
        ->assertViewIs('links.index')
        ->assertSee('My Test Link')
        ->assertSee($this->link->original_url);
});

it('allows users to create a new link', function () {
    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com/docs',
        'title' => 'Laravel Docs',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('status', 'Tautan berhasil diperpendek!');

    $this->assertDatabaseHas('links', [
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com/docs',
        'title' => 'Laravel Docs',
    ]);
});

it('rejects creating a link with an invalid url', function () {
    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'not-a-url',
    ]);

    $response->assertSessionHasErrors(['original_url']);
});

it('allows a user to view link analytics', function () {
    $response = $this->actingAs($this->user)->get("/links/{$this->link->id}");

    $response->assertStatus(200)
        ->assertViewIs('links.show')
        ->assertSee('My Test Link')
        ->assertSee('Timeline Klik');
});

it('prevents a user from viewing another users link analytics', function () {
    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->get("/links/{$this->link->id}");

    $response->assertStatus(403);
});
