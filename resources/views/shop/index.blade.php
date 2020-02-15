@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')

    <div id="shop-app">
        <shop-scanner-page
            get-card-url="{{ route('shop.cards.searchByCode') }}"
        ></shop-scanner-page>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('js/shop.js') }}?v={{ $app_version }}"></script>
@endsection