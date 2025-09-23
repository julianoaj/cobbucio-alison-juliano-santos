<?php

use App\Models\Transaction;
use App\Models\User;
use function Pest\Laravel\actingAs;

pest()->group('transaction');

beforeEach(function () {
    actingAs($user = User::factory()->create());

    $this->user = $user;
});

describe('error cases', function () {
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

    it('requires email when making a transfer', function () {
        $response = $this->post(route('transaction.store'), [
            'type' => 'transfer',
            'amount' => 10,
        ]);

        $response->assertInvalid(['email']);
    });

    it('validates recipient exists when making a transfer', function () {
        $response = $this->post(route('transaction.store'), [
            'type' => 'transfer',
            'amount' => 10,
            'email' => 'missing-user@example.com',
        ]);

        $response->assertInvalid(['to_user_id']);
    });

    it('does not allow transfer when balance is insufficient', function () {
        $recipient = User::factory()->create();

        $response = $this->post(route('transaction.store'), [
            'type' => 'transfer',
            'amount' => 50,
            'email' => $recipient->email,
        ]);

        $response->assertStatus(500);

        expect((string)$this->user->fresh()->wallet->balance)->toBe('0.00')
            ->and((string)$recipient->fresh()->wallet->balance)->toBe('0.00')
            ->and(Transaction::query()->count())->toBe(0);
    });
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

it('stores a transfer and records the transaction', function () {
    expect((string) $this->user->wallet->balance)->toBe('0.00');

    $this->post(route('transaction.store'), [
        'type' => 'deposit',
        'amount' => 200,
    ])->assertSuccessful();

    $recipient = User::factory()->create();

    $response = $this->post(route('transaction.store'), [
        'type' => 'transfer',
        'amount' => 75,
        'email' => $recipient->email,
    ]);

    $response->assertSuccessful();

    $freshSender = $this->user->fresh();
    $freshRecipient = $recipient->fresh();

    expect((string) $freshSender->wallet->balance)->toBe('125.00')
        ->and((string) $freshRecipient->wallet->balance)->toBe('75.00');

    $transaction = Transaction::query()->latest('id')->first();

    expect($transaction)->not()->toBeNull()
        ->and($transaction?->user_id)->toBe($freshSender->id)
        ->and($transaction?->type)->toBe('transfer')
        ->and((float) $transaction?->amount)->toBe(75.0)
        ->and($transaction?->currency)->toBe('BRL')
        ->and($transaction?->status)->toBe('completed')
        ->and($transaction?->to_user_id)->toBe($freshRecipient->id);
});

describe('update transaction', function () {
    it('validates status is required on update', function () {
        $recipient = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'transfer',
            'amount' => 10,
            'to_user_id' => $recipient->id,
            'currency' => 'BRL',
            'status' => 'completed',
        ]);

        $response = $this->patch(route('transaction.update', $transaction), []);

        $response->assertInvalid(['status']);
    });

    it('rejects invalid status value on update', function () {
        $recipient = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'transfer',
            'amount' => 10,
            'to_user_id' => $recipient->id,
            'currency' => 'BRL',
            'status' => 'completed',
        ]);

        $response = $this->patch(route('transaction.update', $transaction), [
            'status' => 'foo',
        ]);

        $response->assertInvalid(['status']);
    });

    it('requires authentication to update a transaction', function () {
        $recipient = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $this->user->id,
            'type' => 'transfer',
            'amount' => 10,
            'to_user_id' => $recipient->id,
            'currency' => 'BRL',
            'status' => 'completed',
        ]);

        auth()->logout();

        $response = $this->patch(route('transaction.update', $transaction), [
            'status' => 'refund',
        ]);

        $response->assertRedirect();
    });

    it('refunds a completed transfer and reverses balances', function () {
        expect((string) $this->user->wallet->balance)->toBe('0.00');

        $this->post(route('transaction.store'), [
            'type' => 'deposit',
            'amount' => 200,
        ])->assertSuccessful();

        $recipient = User::factory()->create();

        $this->post(route('transaction.store'), [
            'type' => 'transfer',
            'amount' => 75,
            'email' => $recipient->email,
        ])->assertSuccessful();

        $transaction = Transaction::query()->latest('id')->first();
        expect($transaction?->type)->toBe('transfer');

        $response = $this->patch(route('transaction.update', $transaction), [
            'status' => 'refund',
        ]);

        $response->assertSuccessful();

        $freshSender = $this->user->fresh();
        $freshRecipient = $recipient->fresh();
        $freshTransaction = $transaction?->fresh();

        expect((string)$freshSender->wallet->balance)->toBe('200.00')
            ->and((string)$freshRecipient->wallet->balance)->toBe('0.00')
            ->and($freshTransaction?->status)->toBe('refunded');
    });
});
