<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Notifications\TransactionNotification;
use Illuminate\Support\Facades\Notification;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->sendToParticipants($transaction, 'created');
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        $this->sendToParticipants($transaction, 'updated');
    }

    protected function sendToParticipants(Transaction $transaction, string $event): void
    {
        $recipients = [];

        if ($transaction->user !== null) {
            $recipients[] = $transaction->user;
        }

        if ($transaction->toUser !== null && $transaction->toUser->id !== $transaction->user_id) {
            $recipients[] = $transaction->toUser;
        }

        if (count($recipients) === 0) {
            return;
        }

        Notification::send($recipients, new TransactionNotification($transaction, $event));
    }
}
