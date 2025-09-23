<?php

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\TransactionNotification;
use Illuminate\Support\Facades\Notification;

pest()->group('transactionNotification');

it('notifies both user and to_user on transaction creation', function () {
    Notification::fake();

    $from = User::factory()->create();
    $to = User::factory()->create();

    Transaction::create([
        'user_id' => $from->id,
        'type' => 'transfer',
        'amount' => 100,
        'to_user_id' => $to->id,
        'currency' => 'BRL',
        'status' => 'pending',
    ]);

    Notification::assertSentTo([$from, $to], TransactionNotification::class);
});

it('notifies both user and to_user on transaction update', function () {
    Notification::fake();

    $from = User::factory()->create();
    $to = User::factory()->create();

    $transaction = Transaction::create([
        'user_id' => $from->id,
        'type' => 'transfer',
        'amount' => 50,
        'to_user_id' => $to->id,
        'currency' => 'BRL',
        'status' => 'pending',
    ]);

    $transaction->update(['status' => 'completed']);

    Notification::assertSentTo([$from, $to], TransactionNotification::class);
});
