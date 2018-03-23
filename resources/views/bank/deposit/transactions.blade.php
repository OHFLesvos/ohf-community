@extends('layouts.app')

@section('title', __('people.deposits'))

@section('content')

    @if( ! $transactions->isEmpty() )
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>@lang('app.date')</th>
                    <th>@lang('people.project')</th>
                    <th class="text-right">@lang('app.amount')</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td title="{{ $transaction->created_at }}">
                        {{ $transaction->created_at->diffForHumans() }}
                        <small class="text-muted">by {{ $transaction->user->name }}</small>
                    </td>
                    <td>{{ $transaction->name }}</td>
                    <td class="text-right">{{ $transaction->value }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $transactions->links() }}
    @endif

@endsection