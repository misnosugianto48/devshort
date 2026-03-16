<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows creating a link with a password', function () {
    $response = $this->actingAs($this->user)->post('/links', [
        'original_url' => 'https://laravel.com',
        'password' => 'secret123',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('status');

    $link = Link::where('original_url', 'https://laravel.com')->first();

    expect($link->password)->not->toBeNull();
    expect(Hash::check('secret123', $link->password))->toBeTrue();
});

it('blocks access to a password protected link and displays the password form', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com',
        'short_code' => 'protected-link',
        'password' => Hash::make('secret123'),
        'is_active' => true,
    ]);

    $response = $this->get('/'.$link->short_code);

    $response->assertStatus(200)
        ->assertViewIs('links.password')
        ->assertSee('Tautan Terkunci');
});

it('rejects an incorrect password', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->post('/links/'.$link->id.'/password', [
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('password');
    expect(session()->has('link_unlocked_'.$link->id))->toBeFalse();
});

it('unlocks the link and redirects to destination with correct password', function () {
    $link = Link::factory()->create([
        'user_id' => $this->user->id,
        'original_url' => 'https://laravel.com',
        'short_code' => 'protected-link-2',
        'password' => Hash::make('secret123'),
        'is_active' => true,
    ]);

    $response = $this->post('/links/'.$link->id.'/password', [
        'password' => 'secret123',
    ]);

    $response->assertRedirect('/'.$link->short_code);
    expect(session()->has('link_unlocked_'.$link->id))->toBeTrue();

    // Secondary test: after unlocking, it should redirect to the actual URL
    $redirectResponse = $this->get('/'.$link->short_code);
    $redirectResponse->assertStatus(301)
        ->assertRedirect($link->original_url);
});
