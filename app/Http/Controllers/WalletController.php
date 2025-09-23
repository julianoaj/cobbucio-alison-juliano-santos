<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Wallet;
use Inertia\Inertia;

class WalletController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(): \Inertia\Response
    {
        return Inertia::render('wallet/Index', [
            'wallet' => request()->user()->wallet->only(['id', 'balance', 'currency']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        //
    }
}
