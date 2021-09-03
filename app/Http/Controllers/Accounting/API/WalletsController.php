<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreWallet;
use App\Http\Resources\Accounting\SimpleWallet;
use App\Models\Accounting\Wallet;
use Illuminate\Http\Request;
use App\Http\Resources\Accounting\Wallet as WalletResource;
use App\Models\Role;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class WalletsController extends Controller
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
    public function index(Request $request)
    {
        $request->validate([
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'name',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 25);

        return WalletResource::collection(Wallet::query()
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Accounting\StoreWallet  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWallet $request)
    {
        $wallet = new Wallet();
        $wallet->fill($request->all());
        $wallet->save();

        if ($request->user()->can('viewAny', Role::class)) {
            $wallet->roles()->sync($request->input('roles', []));
        }

        return new WalletResource($wallet);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        return new WalletResource($wallet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Accounting\StoreWallet  $request
     * @param  \App\Models\Accounting\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWallet $request, Wallet $wallet)
    {
        $wallet->fill($request->all());
        $wallet->save();

        if ($request->user()->can('viewAny', Role::class)) {
            $wallet->roles()->sync($request->input('roles', []));
        }

        return new WalletResource($wallet);
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

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function names(Request $request)
    {
        return Wallet::orderBy('name')
            ->get()
            ->filter(fn ($wallet) => $request->user()->can('view', $wallet))
            ->map(fn ($wallet) => new SimpleWallet($wallet));
    }
}
