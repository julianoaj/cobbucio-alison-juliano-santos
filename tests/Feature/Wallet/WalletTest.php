<?php


use App\Models\User;
use Inertia\Testing\AssertableInertia;

pest()->group('wallet');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('render wallet page and includes all wallet attributes', function (): void {
    $response = $this->actingAs($this->user)->get(route('wallet.show'));

    $expected = $this->user->fresh()->wallet;

    $response->assertStatus(200)
        ->assertInertia(function (AssertableInertia $page) use ($expected): void {
            $page->has('wallet', fn (AssertableInertia $props) => $props
                ->where('id', $expected['id'])
                ->where('user_id', $expected['user_id'])
                ->where('balance', $expected['balance'])
                ->where('currency', $expected['currency'])
                ->has('created_at')
                ->has('updated_at')
            );
        });
});

it('creates a wallet when a user is created', function () {
    $fresh = $this->user->fresh();

    expect($fresh->wallet)->not->toBeNull();
});
