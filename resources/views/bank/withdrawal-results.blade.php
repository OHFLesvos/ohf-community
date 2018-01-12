@extends('layouts.app')

@section('title', 'Bank')

@section('content')

    @include('bank.person-search')

    @if(count($results) > 0)
        @foreach ($results as $person)
            @include('bank.person-card')
        @endforeach
        {{ $results->appends(['filter' => $filter])->links('vendor.pagination.bootstrap-4') }}
    @else
        @component('components.alert.info')
            Not found.
            <a href="{{ route('people.create') }}?{{ $register }}">Register a new person</a>
        @endcomponent
    @endif

@endsection

