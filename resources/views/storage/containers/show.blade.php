@extends('layouts.app')

@section('title', __('storage.view_storage_content'))

@section('content')

    <h2 class="mb-3">{{ $container->name }}</h2>
    @isset($container->description)
        <p><em>{{ $container->description }}</em></p>
    @endif
    @if (! $container->transactions->isEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="fit text-right">@lang('storage.quantity')</th>
                        <th colspan="2">@lang('storage.item')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($container->transactions()->groupBy('item')->select(DB::raw('SUM(quantity) as sum'), 'item')->orderBy('item')->get() as $transaction)
                        <tr class="@if($transaction->sum <= 0) text-danger @endif">
                            <td class="align-middle fit text-right">{{ $transaction->sum }}</td>
                            <td class="align-middle">{{ $transaction->item }}</td>
                            <td class="align-middle fit">
                                @if($transaction->sum > 0)
                                    <a href="{{ route('storage.containers.transactions.remove', $container) }}?item={{ $transaction->item }}" class="btn btn-secondary btn-sm">
                                        @icon(minus-circle)<span class="d-none d-sm-inline"> @lang('storage.take_out')</span>
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
            @lang('storage.no_items_registered')
        @endcomponent
    @endif
	
@endsection
