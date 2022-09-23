<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreWallet;
use App\Http\Resources\Accounting\SimpleWallet;
use App\Http\Resources\Accounting\Wallet as WalletResource;
use App\Models\Accounting\Wallet;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class WalletsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Wallet::class);
    }

    public function index(Request $request): JsonResource
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
            ->get()
            ->filter(fn (Wallet $wallet) => $request->user()->can('view', $wallet))
            ->paginate($pageSize));
    }

    public function store(StoreWallet $request): JsonResource
    {
        $wallet = new Wallet();
        $wallet->fill($request->all());
        $wallet->save();

        if ($request->user()->can('viewAny', Role::class)) {
            $wallet->roles()->sync($request->input('roles', []));
        }

        return new WalletResource($wallet);
    }

    public function show(Wallet $wallet): JsonResource
    {
        return new WalletResource($wallet);
    }

    public function update(StoreWallet $request, Wallet $wallet): JsonResource
    {
        $wallet->fill($request->all());
        $wallet->save();

        if ($request->user()->can('viewAny', Role::class)) {
            $wallet->roles()->sync($request->input('roles', []));
        }

        return new WalletResource($wallet);
    }

    public function destroy(Wallet $wallet): Response
    {
        $wallet->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function names(Request $request): Collection
    {
        return Wallet::orderBy('name')
            ->get()
            ->filter(fn (Wallet $wallet) => $request->user()->can('view', $wallet))
            ->map(fn (Wallet $wallet) => new SimpleWallet($wallet));
    }
}
