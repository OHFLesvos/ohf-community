@extends('bank::layout')

@section('title', __('bank::bank.bank'))

@section('wrapped-content')

    @php
        $lang_arr = lang_arr([
            'app.register',
            'people::people.police_number',
            'people::people.case_number',
            'app.yes',
            'library::library.library',
            'people::people.no_coupons_defined',
            'helpers::helpers.helper',
            'app.undo',
            'people::people.remarks',
            'people::people.click_to_add_remarks',
            'people::people.bank_search_text',
            'people::people.scan_card',
            'app.not_found',
            'people::people.register_a_new_person',
            'people::people.not_yet_served_any_persons',
            'people::people.qr_code_scanner',
            'app.please_wait',
            'app.only_letters_and_numbers_allowed'
        ]);
    @endphp

    <div id="bank-app">
        <bank-search-page
            api-url="{{ route('api.bank.withdrawal.search') }}"
            stats-api-url="{{ route('api.bank.withdrawal.dailyStats') }}"
            :lang='@json($lang_arr)'
            @can('create', Modules\People\Entities\Person::class)can-register-person
            register-person-url="{{ route('bank.people.create') }}" @endcan
        ></bank-search-page>
    </div>

@endsection

@section('script')
    var scannerDialogTitle = '@lang('people::people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
