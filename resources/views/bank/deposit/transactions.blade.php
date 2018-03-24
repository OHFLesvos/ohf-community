@extends('layouts.app')

@section('title', __('people.deposits'))

@section('content')

    @if( ! $transactions->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>@lang('app.registered')</th>
                    <th>@lang('people.project')</th>
                    <th>@lang('app.amount')</th>
                    <th>@lang('app.date')</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td title="{{ $transaction->created_at }}">
                        {{ $transaction->created_at->diffForHumans() }}
                        <small class="text-muted">by {{ $transaction->user->name }}</small>
                    </td>
                    <td>{{ $transaction->project->name }}</td>
                    <td>
                        {{ $transaction->amount }}
                        {{ $transaction->couponType->name }}
                    </td>
                    <td>{{ $transaction->date }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $transactions->links() }}
    @else
        @component('components.alert.info')
            @lang('people.no_transactions_so_far')
        @endcomponent
    @endif

@endsection