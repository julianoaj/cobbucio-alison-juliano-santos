<?php

use App\Models\Transaction;
use App\Models\User;
use function Pest\Laravel\actingAs;

pest()->group('transaction');

beforeEach(function () {
    actingAs($user = User::factory()->create());

    $this->user = $user;
});

it('stores a deposit and records the transaction', function () {
    expect((string) $this->user->wallet->balance)->toBe('0.00');

    $response = $this->post(route('transaction.store'), [
        'type' => 'deposit',
        'amount' => 100,
    ]);

    $response->assertSuccessful();

    $freshUser = $this->user->fresh();

    expect((string) $freshUser->wallet->balance)->toBe('100.00');

    $transaction = Transaction::query()->latest('id')->first();

    expect($transaction)->not()->toBeNull()
        ->and($transaction?->user_id)->toBe($freshUser->id)
        ->and($transaction?->type)->toBe('deposit')
        ->and((float) $transaction?->amount)->toBe(100.0)
        ->and($transaction?->currency)->toBe('BRL')
        ->and($transaction?->status)->toBe('completed')
        ->and($transaction?->to_user_id)->toBeNull();
});

it('validates required fields', function () {
    $response = $this->post(route('transaction.store'), []);

    $response->assertInvalid(['type', 'amount']);
});

it('rejects invalid type', function () {
    $response = $this->post(route('transaction.store'), [
        'type' => 'foo',
        'amount' => 10,
    ]);

    $response->assertInvalid(['type']);
});

it('requires authentication to store a transaction', function () {
    auth()->logout();

    $response = $this->post(route('transaction.store'), [
        'type' => 'deposit',
        'amount' => 10,
    ]);

    $response->assertRedirect();
});
