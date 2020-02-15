@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-app">
        <shop-card-manager-page
            list-cards-url="{{ route('shop.cards.listRedeemedToday') }}"
            summary-url="{{ route('shop.cards.listNonRedeemedByDay') }}"
            delete-url="{{ route('shop.cards.deleteNonRedeemedByDay') }}"
        ></shop-card-manager-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/shop.js') }}?v={{ $app_version }}"></script>
@endsection