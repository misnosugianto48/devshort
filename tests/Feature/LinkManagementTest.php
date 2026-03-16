<?php

use App\Models\Link;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can filter links by search term', function () {
    Link::factory()->create(['user_id' => $this->user->id, 'title' => 'Important Document']);
    Link::factory()->create(['user_id' => $this->user->id, 'title' => 'Random Link']);

    $response = $this->actingAs($this->user)->get('/links?search=Important');

    $response->assertStatus(200);
    $response->assertSee('Important Document');
    $response->assertDontSee('Random Link');
});

it('can filter links by active status', function () {
    Link::factory()->create(['user_id' => $this->user->id, 'title' => 'Active Link', 'is_active' => true]);
    Link::factory()->create(['user_id' => $this->user->id, 'title' => 'Inactive Link', 'is_active' => false]);

    $response = $this->actingAs($this->user)->get('/links?status=inactive');

    $response->assertStatus(200);
    $response->assertSee('Inactive Link');
    $response->assertDontSee('Active Link');
});

it('can update link details', function () {
    $link = Link::factory()->create(['user_id' => $this->user->id, 'title' => 'Old Title', 'is_active' => false]);

    $response = $this->actingAs($this->user)->put('/links/'.$link->id, [
        'title' => 'New Title',
        'is_active' => '1',
    ]);

    $response->assertRedirect();

    $link->refresh();
    expect($link->title)->toBe('New Title');
    expect($link->is_active)->toBeTrue();
});

it('can soft delete a link', function () {
    $link = Link::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->delete('/links/'.$link->id);

    $response->assertRedirect();
    $this->assertSoftDeleted('links', ['id' => $link->id]);
});

it('can perform bulk delete action', function () {
    $link1 = Link::factory()->create(['user_id' => $this->user->id]);
    $link2 = Link::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->post('/links/bulk', [
        'action' => 'delete',
        'ids' => [$link1->id, $link2->id],
    ]);

    $response->assertRedirect();
    $this->assertSoftDeleted('links', ['id' => $link1->id]);
    $this->assertSoftDeleted('links', ['id' => $link2->id]);
});

it('can perform bulk activate action', function () {
    $link1 = Link::factory()->create(['user_id' => $this->user->id, 'is_active' => false]);
    $link2 = Link::factory()->create(['user_id' => $this->user->id, 'is_active' => false]);

    $response = $this->actingAs($this->user)->post('/links/bulk', [
        'action' => 'activate',
        'ids' => [$link1->id, $link2->id],
    ]);

    $response->assertRedirect();

    $link1->refresh();
    $link2->refresh();
    expect($link1->is_active)->toBeTrue();
    expect($link2->is_active)->toBeTrue();
});
