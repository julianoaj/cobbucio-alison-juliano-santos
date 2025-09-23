<?php

namespace App\Actions\Wallet;

use App\Interfaces\Action;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class Deposit implements Action
{
    public function handle(Request $request): void
    {
        $wallet = $request->user()->wallet;

        $wallet->update([
            'balance' => $wallet->balance + $request->amount
        ]);
    }
}
