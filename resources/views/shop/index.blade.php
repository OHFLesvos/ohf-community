@extends('layouts.app')

@section('title', __('shop.shop'))

@section('content')
    <div id="shop-app">
        <shop-scanner-page>
            @lang('app.loading')
        </shop-scanner-page>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('js/shop.js') }}?v={{ $app_version }}"></script>
@endpush
