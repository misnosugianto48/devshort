<?php

use App\Models\User;

test('authenticated users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'));

    $this->assertGuest();
});
