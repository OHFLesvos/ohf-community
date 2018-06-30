@extends('layouts.app')

@section('title', __('inventory.view_storage_content'))

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
                        <th class="fit text-right">@lang('inventory.quantity')</th>
                        <th colspan="2">@lang('inventory.item')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storage->transactions()->groupBy('item')->select(DB::raw('SUM(quantity) as sum'), 'item')->orderBy('item')->get() as $transaction)
                        <tr class="@if($transaction->sum <= 0) text-danger @endif">
                            <td class="align-middle fit text-right">{{ $transaction->sum }}</td>
                            <td class="align-middle">
                                <a href="{{ route('inventory.transactions.changes', $storage) }}?item={{ $transaction->item }}">
                                    {{ $transaction->item }}
                                </a>
                            </td>
                            <td class="align-middle fit">
                                @if($transaction->sum > 0)
                                    <a href="{{ route('inventory.transactions.egress', $storage) }}?item={{ $transaction->item }}" class="btn btn-secondary btn">
                                        @icon(minus-circle)<span class="d-none d-sm-inline"> @lang('inventory.take_out')</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('inventory.no_items_registered')
        @endcomponent
    @endif
	
@endsection
