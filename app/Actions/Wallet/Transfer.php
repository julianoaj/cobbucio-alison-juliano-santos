<?php

namespace App\Actions\Wallet;

use App\Interfaces\Action;
use App\Models\User;
use Illuminate\Http\Request;

class Transfer implements Action
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function handle(Request $request): void
    {
        $fromWallet = $request->user()->wallet;
        $toWallet = User::findOrFail($request->to_user_id)->wallet;

        if ($fromWallet->balance < $request->amount) {
            throw new \Exception("Saldo insuficiente");
        }

        $fromWallet->update([
            'balance' => $fromWallet->balance - $request->amount
        ]);

        $toWallet->update([
            'balance' => $toWallet->balance + $request->amount
        ]);
    }
}
