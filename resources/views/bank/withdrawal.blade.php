@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    @include('bank.person-search')

    <div id="stats" class="text-center lead my-5">
        @if($numberOfPersonsServed > 0)
            Today, we served <strong>{{ $numberOfPersonsServed }}</strong> persons, 
            handing out <strong>{{ $transactionValue }}</strong> drachmas.        
        @else
            We did not yet serve any persons today.
        @endif
    </div>

    @if( ! $latestTransactions->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Person</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($latestTransactions as $transaction)
                    <tr>
                        <td title="{{ $transaction->created_at }}">
                            {{ $transaction->created_at->diffForHumans() }}
                            <small class="text-muted">by {{ $transaction->user->name }}</small>
                        </td>
                        <td>
                            <a href="{{ route('people.show', $transaction->transactionable) }}">{{ $transaction->transactionable->family_name }} {{ $transaction->transactionable->name }}</a>
                        </td>
                        <td>{{ $transaction->value }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
@endsection

@section('footer')
    <script src="{{asset('js/bank.js')}}?v={{ $app_version }}"></script>
@endsection