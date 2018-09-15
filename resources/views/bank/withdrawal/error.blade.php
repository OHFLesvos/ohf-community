@extends('bank.layout')

@section('title', __('people.bank'))

@section('wrapped-content')
    <div id="bank-container">

        @component('components.alert.warning')
            {{ $message }}
        @endcomponent

        <div class="text-right">
            <button class="btn btn-secondary btn" type="button" id="scan-id-button">@icon(qrcode)<span class="d-none d-sm-inline"> @lang('people.scan_another_card')</span></button> 
            <a class="btn btn-secondary btn" href="{{ route('bank.withdrawal') }}">@icon(search)<span class="d-none d-sm-inline"> @lang('people.search_persons')</span></a>
        </div>        

    </div>
@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var handoutCouponUrl = '{{ route('bank.handoutCoupon') }}';
    var undoHandoutCouponUrl = '{{ route('bank.undoHandoutCoupon') }}';
    var updateGenderUrl = '{{ route('bank.updateGender') }}';
    var updateDateOfBirthUrl = '{{ route('bank.updateDateOfBirth') }}';
    var updateNationalityUrl = '{{ route('bank.updateNationality') }}';
    var registerCardUrl = '{{ route('bank.registerCard') }}';
    var undoLabel = '@lang('app.undo')';
    var scannerDialogTitle = '@lang('people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection
