@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-app">
        @php
            $lang_arr = lang_arr([
                'app.searching',
                'app.loading',
                'shop.card_not_registered',
                'shop.person_assigned_to_card_has_been_deleted',
                'shop.cancel_card',
                'shop.redeem',
                'shop.scan_card',
                'shop.scan_another_card',
                'shop.card',
                'shop.registered',
                'shop.should_card_be_cancelled',
                'shop.card_already_redeemed',
                'shop.card_expired',
                'shop.please_scan_next_card',
                'shop.please_enable_scanner_to_scan_cards',
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