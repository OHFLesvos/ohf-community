@extends('layouts.app')

@section('title', __('people.register_person'))

@section('content')

    @php
        $lang_arr = lang_arr([
            'people.name',
            'people.family_name',
            'people.gender',
            'people.police_number',
            'people.leading_zeros_added_automatically',
            'people.date_of_birth',
            'people.age',
            'people.nationality',
            'people.remarks',
            'app.register',
            'app.male',
            'app.female',
            'people.qr_code_scanner',
            'app.only_letters_and_numbers_allowed',
            'people.code_card',
        ]);
    @endphp

    <div id="bank-app">
        <bank-register-person-page
            api-url="{{ route('api.people.store') }}"
            redirect-url="{{ route('bank.withdrawal.search') }}"
            :countries='@json($countries)'
            :lang='@json($lang_arr)'
            name="{{ request()->query('name') }}"
            family-name="{{ request()->query('family_name') }}"
            police-no="{{ request()->query('police_no') }}"
        ></bank-register-person-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}" defer></script>
@endsection
