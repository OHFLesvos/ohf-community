@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    @include('bank.person-search')

    <div id="stats" class="text-center lead my-5">
        @if($stats['numberOfPersonsServed'] > 0)
            Today, we served <strong>{{ $stats['numberOfPersonsServed'] }}</strong> persons, 
            handing out <strong>{{ $stats['transactionValue'] }}</strong> drachmas.        
        @else
            We did not yet serve any persons today.
        @endif
    </div>

@endsection

