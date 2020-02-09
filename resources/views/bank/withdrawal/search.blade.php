@extends('bank.layout')

@section('title', __('bank.bank'))

@section('wrapped-content')

    @php
        $lang_arr = lang_arr([
            'app.register',
            'people.police_number',
            'people.case_number',
            'app.yes',
            'library::library.library',
            'people.no_coupons_defined',
            'helpers::helpers.helper',
            'app.undo',
            'people.remarks',
            'people.click_to_add_remarks',
            'people.bank_search_text',
            'people.scan_card',
            'app.not_found',
            'people.register_a_new_person',
            'people.not_yet_served_any_persons',
            'people.qr_code_scanner',
            'app.please_wait',
            'app.only_letters_and_numbers_allowed'
        ]);
    @endphp

    <div id="bank-app">
        <bank-search-page
            api-url="{{ route('api.bank.withdrawal.search') }}"
            stats-api-url="{{ route('api.bank.withdrawal.dailyStats') }}"
            :lang='@json($lang_arr)'
            @can('create', App\Models\People\Person::class)can-register-person
            register-person-url="{{ route('bank.people.create') }}" @endcan
        ></bank-search-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
