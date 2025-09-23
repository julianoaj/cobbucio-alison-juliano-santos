<?php

namespace App\Actions\Wallet;

use App\Interfaces\Action;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;

class Refaund
{
    /**
     * @throws Exception
     */
    public function handle(Transaction $transaction): void
    {
        $fromWallet = $transaction->user->wallet;
        $toWallet = $transaction->toUser->wallet;

        $toWallet->update([
            'balance' => $toWallet->balance - $transaction->amount
        ]);

        $fromWallet->update([
            'balance' => $fromWallet->balance + $transaction->amount
        ]);
    }
}
