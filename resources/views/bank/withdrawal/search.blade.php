@extends('layouts.app')

@section('title', __('bank.bank'))

@section('content')
    <div id="bank-app">
        <bank-search-page
            @can('create', App\Models\People\Person::class)can-register-person
            register-person-url="{{ route('bank.people.create') }}" @endcan
        >
            @lang('app.loading')
        </bank-search-page>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
