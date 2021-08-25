<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(Wallet $wallet, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);
        $this->authorize('view', $wallet);

        return view('accounting.transactions', [
            'wallet' => $wallet,
        ]);
    }

    public function create(Wallet $wallet)
    {
        $this->authorize('create', Transaction::class);

        return view('accounting.transactions');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting.transactions');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('accounting.transactions');
    }
}
