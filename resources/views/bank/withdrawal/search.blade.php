@extends('layouts.app')

@section('title', __('bank.bank'))

@section('content')

    <div id="bank-app">
        <bank-search-page
            :countries='@json(array_values(Countries::getList('en')))'
            @can('create', App\Models\People\Person::class)can-register-person
            register-person-url="{{ route('bank.people.create') }}" @endcan
        ></bank-search-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
