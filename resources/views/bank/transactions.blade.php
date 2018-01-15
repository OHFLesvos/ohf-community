@extends('layouts.app')

@section('title', 'Bank')

@section('content')
    @if( ! $transactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Person</th>
                        <th>Drachma</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td title="{{ $transaction->created_at }}">
                            {{ $transaction->created_at->diffForHumans() }}
                            @if(isset($transaction->user))
                                <small class="text-muted">by {{ $transaction->user->name }}</small>
                            @endif
                        </td>
                        <td>
                            @if($transaction->transactionable != null)
                                <a href="{{ route('people.show', $transaction->transactionable) }}">{{ $transaction->transactionable->family_name }} {{ $transaction->transactionable->name }}</a>
                                @if($transaction->transactionable->gender == 'f')@icon(female) 
                                @elseif($transaction->transactionable->gender == 'm')@icon(male) 
                                @endif
                                @if(isset($transaction->transactionable->date_of_birth))
                                    {{ $transaction->transactionable->date_of_birth }} (age {{ $transaction->transactionable->age }})
                                @endif
                                @if(isset($transaction->transactionable->nationality))
                                    {{ $transaction->transactionable->nationality }}
                                @endif
                            @else
                                <em>Person deleted</em>
                            @endif
                        </td>
                        <td>{{ $transaction->value }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $transactions->links('vendor.pagination.bootstrap-4') }}
        </div>
    @else
        @component('components.alert.info')
            No transactions so far.
        @endcomponent
    @endif
@endsection
