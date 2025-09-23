<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function index(): JsonResponse
    {
        $transactions = Transaction::with('toUser')->latest()->paginate(10);

        return response()->json(TransactionResource::collection($transactions), 200);
    }
}
