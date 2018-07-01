@extends('layouts.app')

@section('title', __('inventory.transactions'))

@section('content')

    <h4 class="mb-3">@lang('inventory.changes_of_stock_in_storage', ['item' => request()->item, 'storage' => $storage->name ])</h4>
    @if (! $transactions->isEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="fit">@lang('app.date')</th>
                        <th class="fit text-right">
                            <span class="d-none d-sm-inline">@lang('inventory.quantity')</span>
                            <span class="d-inline d-sm-none">#</span>
                        </th>
                        <th class="fit text-right">
                            <span class="d-none d-sm-inline">@lang('app.total')</span>
                            <span class="d-inline d-sm-none">&Sigma;</span>
                        </th>
                        <th>@lang('inventory.origin') / @lang('inventory.destination')</th>
                        <th class="d-none d-sm-table-cell">@lang('inventory.sponsor')</th>
                        <th class="fit d-none d-sm-table-cell">@lang('app.user')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="fit text-right" title="{{ $transaction->created_at }}">
                                {{ $transaction->created_at->diffForHumans() }}
                            </td>
                            <td class="fit text-right @if($transaction->quantity > 0) text-success @else text-danger @endif ">
                                {{ $transaction->quantity }}
                            </td>
                            <td class="fit text-right">
                                @php
                                    echo App\InventoryItemTransaction
                                        ::where('item', $transaction->item)
                                        ->where('created_at', '<=', $transaction->created_at)
                                        ->select(DB::raw('sum(quantity) as total'))
                                        ->groupBy('item')
                                        ->first()
                                        ->total;
                                @endphp
                            </td>
                            <td>
                                @if($transaction->quantity > 0)
                                    {{ $transaction->origin }}
                                @else
                                    {{ $transaction->destination }}
                                @endif
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{ $transaction->sponsor }}
                            </td>
                            <td class="fit d-none d-sm-table-cell">
                                @isset($transaction->user_id)
                                    @can('view', $transaction->user)
                                        <a href="{{ route('users.show', $transaction->user) }}">{{ $transaction->user_name }}</a>
                                    @else
                                        {{ $transaction->user_name }}
                                    @endcan
                                @endisset
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $transactions->appends(['item' => request()->item])->links() }}
    @else
        @component('components.alert.info')
            @lang('inventory.no_items_found')
        @endcomponent
    @endif
	
@endsection
