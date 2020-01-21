@extends('layouts.app')

@section('title', __('shop::shop.shop'))

@section('content')

    <div id="shop-app">
        @php
            $lang_arr = lang_arr([
                'app.searching',
                'app.loading',
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
                'shop::shop.please_scan_next_card',
                'shop::shop.please_enable_scanner_to_scan_cards',
                'app.only_letters_and_numbers_allowed'
            ]);
        @endphp
        <shop-scanner-page
            get-card-url="{{ route('shop.cards.searchByCode') }}"
            :lang='@json($lang_arr)'
        ></shop-scanner-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/shop.js') }}?v={{ $app_version }}"></script>
@endsection