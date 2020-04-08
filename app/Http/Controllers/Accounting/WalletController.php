<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreWallet;
use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;
use App\Services\Accounting\CurrentWalletService;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Wallet::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accounting.wallets.index', [
            'wallets' => Wallet::orderBy('name')
                ->with('transactions')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounting.wallets.create', [ ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWallet $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWallet $request)
    {
        $wallet = new Wallet();
        $wallet->fill($request->all());
        $wallet->is_default = isset($request->is_default);
        $wallet->save();

        return redirect()
            ->route('accounting.wallets.index')
            ->with('success', __('accounting.wallet_added'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        return view('accounting.wallets.edit', [
            'wallet' => $wallet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreWallet $request
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWallet $request, Wallet $wallet)
    {
        $wallet->fill($request->all());
        $wallet->is_default = isset($request->is_default);
        $wallet->save();

        return redirect()
            ->route('accounting.wallets.index')
            ->with('info', __('accounting.wallet_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()
            ->route('accounting.wallets.index')
            ->with('success', __('accounting.wallet_deleted'));
    }

    /**
     * List wallets so the user can change the default one in his session
     *
     * @param CurrentWalletService $currentWallet
     * @return void
     */
    public function change(CurrentWalletService $currentWallet)
    {
        $this->authorize('list', MoneyTransaction::class);

        return view('accounting.wallets.change', [
            'wallets' => Wallet::orderBy('is_default', 'desc')
                ->orderBy('name')
                ->get(),
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
        $this->authorize('list', MoneyTransaction::class);

        $currentWallet->set($wallet);

        return redirect()
            ->route('accounting.transactions.index')
            ->with('info', __('accounting.wallet_changed'));
    }
}
