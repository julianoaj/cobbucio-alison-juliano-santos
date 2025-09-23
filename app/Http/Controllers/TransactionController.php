<?php

namespace App\Http\Controllers;

use App\Actions\Wallet\Deposit;
use App\Actions\Wallet\Refaund;
use App\Actions\Wallet\Transfer;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Exception;

class TransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(StoreTransactionRequest $request)
    {
        match ($request->type) {
            'deposit' => app(Deposit::class)->handle($request),
            'transfer' => app(Transfer::class)->handle($request),
            default => throw new Exception("Tipo de transação inválido"),
        };

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'to_user_id' => $request->to_user_id,
            'currency' => "BRL",
            'status' => 'completed'
        ]);

        return response($transaction,200);
    }


    /**
     * Update the specified transaction in store.
     */
    public function update(Transaction $transaction)
    {
        request()->validate([
            'status' => 'required|in:refund'
        ]);

        if ($transaction->status === 'refund') {
            return response(['message' => 'Transação já estornada'],400);
        }

        app(Refaund::class)->handle($transaction);

        $transaction->update([
            'status' => 'refunded'
        ]);

        $transaction->fresh();

        return response($transaction,200);
    }
}
