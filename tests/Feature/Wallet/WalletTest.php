<?php


use App\Models\User;
use Inertia\Testing\AssertableInertia;

pest()->group('wallet');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('creates a wallet when a user is created', function () {
    $fresh = $this->user->fresh();

    expect($fresh->wallet)->not->toBeNull();
});

it('renders the wallet page for a new user with all props', function () {
    expect($this->user->wallet)->not()->toBeNull()
        ->and((string)$this->user->wallet->balance)->toBe('0.00')
        ->and($this->user->wallet->currency)->toBe('BRL');

    $response = $this->actingAs($this->user)->get(route('wallet.show'));

    $response->assertSuccessful();

    $response->assertInertia(fn (AssertableInertia $page) => ($page)
        ->component('wallet/Index')
        ->has('wallet', fn (AssertableInertia $wallet) => $wallet
            ->where('currency', 'BRL')
            ->where('balance', '0.00')
            ->etc()
        )
    );
});
