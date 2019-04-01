@extends('layouts.app')

@section('title', __('inventory::inventory.view_storage_content'))

@section('content')

    <h2 class="mb-3">{{ $storage->name }}</h2>
    @isset($storage->description)
        <p><em>{{ $storage->description }}</em></p>
    @endif
    @if (! $storage->transactions->isEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="fit text-right">
                            <span class="d-none d-sm-inline">@lang('inventory::inventory.quantity')</span>
                            <span class="d-inline d-sm-none">#</span>
                        </th>
                        <th colspan="2">@lang('inventory::inventory.item')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storage->transactions()->groupBy('item')->select(DB::raw('SUM(quantity) as sum'), 'item')->orderBy('item')->get() as $transaction)
                        <tr class="@if($transaction->sum <= 0) text-danger @endif">
                            <td class="align-middle fit text-right">{{ $transaction->sum }}</td>
                            <td class="align-middle">
                                @can('list', Modules\Inventory\Entities\InventoryItemTransaction::class)
                                    <a href="{{ route('inventory.transactions.changes', $storage) }}?item={{ $transaction->item }}">
                                        {{ $transaction->item }}
                                    </a>
                                @else
                                    {{ $transaction->item }}
                                @endcan
                            </td>
                            @can('create', Modules\Inventory\Entities\InventoryItemTransaction::class)
                                <td class="align-middle fit">
                                    <a href="{{ route('inventory.transactions.ingress', $storage) }}?item={{ $transaction->item }}" class="btn btn-secondary btn">
                                        @icon(plus-circle)<span class="d-none d-sm-inline"> @lang('inventory::inventory.store')</span>
                                    </a>
                                    @if($transaction->sum > 0)
                                        <a href="{{ route('inventory.transactions.egress', $storage) }}?item={{ $transaction->item }}" class="btn btn-secondary btn">
                                            @icon(minus-circle)<span class="d-none d-sm-inline"> @lang('inventory::inventory.take_out')</span>
                                        </a>
                                    @endif
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('inventory::inventory.no_items_registered')
        @endcomponent
    @endif
	
@endsection
