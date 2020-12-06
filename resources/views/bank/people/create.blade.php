@extends('layouts.app')

@section('title', __('people.register_person'))

@section('content')

    <div id="bank-app">
        <register-person-page
            redirect-url="{{ route('bank.withdrawal.search') }}"
            :countries='@json($countries)'
            name="{{ request()->query('name') }}"
            family-name="{{ request()->query('family_name') }}"
            police-no="{{ request()->query('police_no') }}"
        ></register-person-page>
    </div>

@endsection

@push('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}" defer></script>
@endpush
