<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;

class WalletController extends Controller
{
    /**
     * List wallets so the user can change the default one in his session
     *
     * @return void
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        return view('accounting.index', [
            'wallets' => Wallet::orderBy('name')
                ->get()
                ->filter(fn ($wallet) => request()->user()->can('view', $wallet)),
        ]);
    }
}
