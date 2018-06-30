@extends('layouts.app')

@section('title', __('storage.view_storage_content'))

@section('content')

    <h4 class="mb-3">@lang('storage.changes_of_stock_in_container', ['item' => request()->item, 'container' => $container->name ])</h4>
    @if (! $transactions->isEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="fit">@lang('app.date')</th>
                        <th class="fit text-right">@lang('storage.quantity')</th>
                        <th class="fit text-right">@lang('app.total')</th>
                        <th colspan="2">@lang('app.user')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="align-middle fit text-right" title="{{ $transaction->created_at }}">
                                {{ $transaction->created_at->diffForHumans() }}
                            </td>
                            <td class="align-middle fit text-right @if($transaction->quantity > 0) text-success @else text-danger @endif ">
                                {{ $transaction->quantity }}
                            </td>
                            <td class="align-middle fit text-right">
                                {{ $transaction->total }}
                            </td>
                            <td class="align-middle">
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
            @lang('storage.no_items_found')
        @endcomponent
    @endif
	
@endsection
