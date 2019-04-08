@extends('bank::layout')

@section('title', __('bank::bank.bank'))

@section('wrapped-content')

    <div id="bank-container">

        @include('bank::person-search')

        <div id="stats" class="text-center lead my-5">
            @if($numberOfPersonsServed > 0)
                {!! __('people::people.num_persons_served_handing_out_coupons', [
                    'persons' => $numberOfPersonsServed,
                    'coupons' => $numberOfCouponsHandedOut,
                ]) !!}
            @else
                @lang('people::people.not_yet_served_any_persons')
            @endif
        </div>

    </div>

@endsection

@section('script')
    var csrfToken = '{{ csrf_token() }}';
    var scannerDialogTitle = '@lang('people::people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
@endsection

@section('footer')
    <script src="{{ asset('js/bank.js') }}?v={{ $app_version }}"></script>
@endsection