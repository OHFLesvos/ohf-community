<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use App\Services\Accounting\CurrentWalletService;

class WalletController extends Controller
{
    /**
     * List wallets so the user can change the default one in his session
     *
     * @param CurrentWalletService $currentWallet
     * @return void
     */
    public function change(CurrentWalletService $currentWallet)
    {
        $this->authorize('viewAny', MoneyTransaction::class);

        return view('accounting.wallets.change', [
            'wallets' => Wallet::orderBy('name')
                ->get()
                ->filter(fn ($wallet) => request()->user()->can('view', $wallet)),
            'active' => $currentWallet->get(),
        ]);
    }

    /**
     * Change the default wallet in the user session
     *
     * @param Wallet $wallet
     * @param CurrentWalletService $currentWallet
     * @return void
     */
    public function doChange(Wallet $wallet, CurrentWalletService $currentWallet)
    {
        $this->authorize('viewAny', MoneyTransaction::class);
        $this->authorize('view', $wallet);

        $change = optional($currentWallet->get())->id != $wallet->id;

        $currentWallet->set($wallet);

        $ret = redirect()
            ->route('accounting.transactions.index');
        if ($change) {
            return $ret->with('info', __('accounting.wallet_changed'));
        }
        return $ret;
    }
}
