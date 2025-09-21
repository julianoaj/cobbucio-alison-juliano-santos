<?php


use App\Models\User;

pest()->group('wallet');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('render wallet page', function () {
    $response = $this->actingAs($this->user)->get(route('wallet.show'));

    $response->assertStatus(200);
});

it('creates a wallet when a user is created', function () {
    $fresh = $this->user->fresh();

    expect($fresh->wallet)->not->toBeNull();
});
