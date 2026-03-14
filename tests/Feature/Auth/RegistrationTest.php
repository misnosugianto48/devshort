<?php

use App\Models\User;

test('registration page can be rendered', function () {
    $this->get(route('register'))
        ->assertSuccessful();
});

test('new users can register', function () {
    $response = $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice'));
});

test('registration requires valid data', function (array $data, string $errorField) {
    $this->post(route('register'), $data)
        ->assertSessionHasErrors($errorField);
})->with([
    'missing name' => [
        ['name' => '', 'email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'password'],
        'name',
    ],
    'missing email' => [
        ['name' => 'Test', 'email' => '', 'password' => 'password', 'password_confirmation' => 'password'],
        'email',
    ],
    'invalid email' => [
        ['name' => 'Test', 'email' => 'not-an-email', 'password' => 'password', 'password_confirmation' => 'password'],
        'email',
    ],
    'missing password' => [
        ['name' => 'Test', 'email' => 'test@example.com', 'password' => '', 'password_confirmation' => ''],
        'password',
    ],
    'password mismatch' => [
        ['name' => 'Test', 'email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'different'],
        'password',
    ],
]);

test('registration fails with duplicate email', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->post(route('register'), [
        'name' => 'Test User',
        'email' => 'taken@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('email');
});
