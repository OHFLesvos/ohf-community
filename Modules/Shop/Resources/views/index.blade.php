@extends('layouts.app')

@section('title', __('shop::shop.shop'))

@section('content')

    <div id="shop-app">
        @php
            $lang_arr = lang_arr([
                'app.searching',
                'shop::shop.card_not_registered',
                'shop::shop.person_assigned_to_card_has_been_deleted',
                'shop::shop.cancel_card',
                'shop::shop.redeem',
                'shop::shop.scan_card',
                'shop::shop.scan_another_card',
                'shop::shop.card',
                'shop::shop.registered',
                'shop::shop.should_card_be_cancelled',
                'shop::shop.card_already_redeemed',
                'shop::shop.card_expired',
                'shop::shop.redeemed_cards'
            ]);
        @endphp
        <shop-app
            list-cards-url="{{ route('shop.listCards') }}"
            get-card-url="{{ route('shop.getCard') }}"
            :lang='@json($lang_arr)'
        ></shop-app>
    </div>

@endsection

@section('script')
    var scannerDialogTitle = '@lang('people::people.qr_code_scanner')';
    var scannerDialogWaitMessage = '@lang('app.please_wait')';
@endsection

@section('footer')
    <script src="{{ asset('js/shop.js') }}?v={{ $app_version }}"></script>
@endsection